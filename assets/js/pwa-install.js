(function () {
	'use strict';
	const config = window.fpwaiConfig;
	if (!config) return;
	const displayMode = window.matchMedia('(display-mode: standalone), (display-mode: fullscreen), (display-mode: minimal-ui)');
	const iosSafari = (/iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)) && /Safari/.test(navigator.userAgent) && !/CriOS|FxiOS|EdgiOS|OPiOS/.test(navigator.userAgent);
	let installed = displayMode.matches || navigator.standalone === true;
	let deferredPrompt = null;
	let busy = false;
	let button;
	let dialog;
	let returnFocus;
	let ownManifest;

	function customTrigger() {
		return config.triggerId ? document.getElementById(config.triggerId) : null;
	}

	function updateUI() {
		if (button) {
			button.hidden = installed || !window.isSecureContext || (!deferredPrompt && !iosSafari);
			button.disabled = busy;
		}
		const trigger = customTrigger();
		if (installed && trigger) {
			trigger.hidden = true;
			trigger.setAttribute('data-fpwai-installed', '');
		}
	}

	function showMessage(message) {
		if (!window.HTMLDialogElement || !HTMLDialogElement.prototype.showModal) {
			window.alert(message);
			return;
		}
		if (!dialog) {
			dialog = document.createElement('dialog');
			dialog.id = 'fpwai-dialog';
			dialog.setAttribute('aria-labelledby', 'fpwai-dialog-title');
			dialog.setAttribute('aria-describedby', 'fpwai-dialog-message');
			const title = document.createElement('h2');
			title.id = 'fpwai-dialog-title';
			title.textContent = config.messages.title;
			const text = document.createElement('p');
			text.id = 'fpwai-dialog-message';
			const close = document.createElement('button');
			close.type = 'button';
			close.textContent = config.messages.close;
			close.addEventListener('click', function () { dialog.close(); });
			dialog.addEventListener('close', function () {
				if (returnFocus && returnFocus.isConnected && !returnFocus.hidden) returnFocus.focus();
			});
			dialog.append(title, text, close);
			document.body.appendChild(dialog);
		}
		document.getElementById('fpwai-dialog-message').textContent = message;
		if (!dialog.open) {
			returnFocus = document.activeElement;
			dialog.showModal();
		}
	}

	async function install() {
		if (installed || busy) return;
		if (!deferredPrompt) {
			showMessage(iosSafari ? config.messages.ios : config.messages.unavailable);
			return;
		}
		const prompt = deferredPrompt;
		deferredPrompt = null;
		busy = true;
		updateUI();
		try {
			await prompt.prompt();
			const choice = await prompt.userChoice;
			if (choice.outcome === 'accepted') installed = true;
		} catch (error) {
			showMessage(config.messages.unavailable);
		} finally {
			busy = false;
			updateUI();
		}
	}

	window.addEventListener('beforeinstallprompt', function (event) {
		event.preventDefault();
		deferredPrompt = event;
		updateUI();
	});
	window.addEventListener('appinstalled', function () {
		installed = true;
		deferredPrompt = null;
		if (dialog && dialog.open) dialog.close();
		updateUI();
	});
	if (displayMode.addEventListener) {
		displayMode.addEventListener('change', function () {
			installed = installed || displayMode.matches;
			updateUI();
		});
	}

	function foreignManifest() {
		return Array.from(document.querySelectorAll('link[rel~="manifest"]')).some(function (link) { return link !== ownManifest; });
	}

	async function registerWorker() {
		if (!ownManifest || foreignManifest() || !window.isSecureContext || !('serviceWorker' in navigator)) return;
		try {
			const scope = new URL(config.scope, location.origin).href;
			const workerUrl = new URL(config.workerUrl, location.href);
			if (workerUrl.origin !== location.origin) return;
			const registrations = await navigator.serviceWorker.getRegistrations();
			for (const registration of registrations) {
				if (!scope.startsWith(registration.scope) && !registration.scope.startsWith(scope)) continue;
				const workers = [registration.active, registration.waiting, registration.installing].filter(Boolean);
				if (workers.some(function (worker) { return worker.scriptURL !== workerUrl.href; })) return;
			}
			const response = await fetch(workerUrl.href, { method: 'HEAD', cache: 'no-store', credentials: 'same-origin' });
			if (!response.ok || !response.headers.get('X-FPWAI-Worker') || foreignManifest()) return;
			await navigator.serviceWorker.register(workerUrl.href, { scope: scope, updateViaCache: 'none' });
		} catch (error) {
			// A rejected registration must never interrupt the site.
		}
	}

	function ready() {
		button = document.getElementById('fpwai-button');
		updateUI();
		document.addEventListener('click', function (event) {
			const trigger = customTrigger();
			const target = event.target;
			if (!(target instanceof Node) || !((button && button.contains(target)) || (trigger && trigger.contains(target)))) return;
			event.preventDefault();
			install();
		});
		if (config.manageManifest && !foreignManifest()) {
			ownManifest = document.createElement('link');
			ownManifest.rel = 'manifest';
			ownManifest.href = config.manifestUrl;
			document.head.appendChild(ownManifest);
			// Prefer foreign manifests even when another provider inserts its link later.
			new MutationObserver(function () {
				if (ownManifest && foreignManifest()) {
					ownManifest.remove();
					ownManifest = null;
				}
			}).observe(document.head, { childList: true, subtree: true, attributes: true, attributeFilter: ['rel'] });
			if (document.readyState === 'complete') registerWorker();
			else window.addEventListener('load', registerWorker, { once: true });
		}
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready, { once: true });
	else ready();
}());
