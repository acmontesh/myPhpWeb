function closeAllDropdowns(except) {
    document.querySelectorAll('.dropdown.is-open').forEach((dropdown) => {
        if (dropdown !== except) {
            dropdown.classList.remove('is-open');
        }
    });
}

function initSidebarGroups() {
    document.querySelectorAll('.sidebar__group-toggle').forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            toggle.closest('.sidebar__group').classList.toggle('is-open');
        });
    });
}

function initDropdowns() {
    document.querySelectorAll('.dropdown__toggle').forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            const dropdown = toggle.closest('.dropdown');
            const wasOpen = dropdown.classList.contains('is-open');
            closeAllDropdowns();
            dropdown.classList.toggle('is-open', !wasOpen);
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.dropdown')) {
            closeAllDropdowns();
        }
    });
}

function initSidebarOffCanvas() {
    const shell = document.querySelector('.dashboard-shell');
    const toggle = document.querySelector('.topbar__toggle');
    const backdrop = document.querySelector('.dashboard-shell__backdrop');

    if (!shell || !toggle) {
        return;
    }

    toggle.addEventListener('click', (event) => {
        event.preventDefault();
        shell.classList.toggle('is-sidebar-open');
    });

    backdrop?.addEventListener('click', () => {
        shell.classList.remove('is-sidebar-open');
    });
}

function initTabs() {
    document.querySelectorAll('[data-tabs]').forEach((group) => {
        const triggers = group.querySelectorAll('[data-tab-trigger]');
        const panels = group.querySelectorAll('[data-tab-panel]');

        triggers.forEach((trigger) => {
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                const target = trigger.dataset.tabTrigger;

                triggers.forEach((t) => t.classList.toggle('is-active', t === trigger));
                panels.forEach((panel) => panel.classList.toggle('is-active', panel.dataset.tabPanel === target));
            });
        });
    });
}

function initConfirmForms() {
    const modal = document.querySelector('[data-confirm-modal]');

    if (!modal) {
        return;
    }

    const messageEl = modal.querySelector('[data-confirm-message]');
    const confirmBtn = modal.querySelector('[data-confirm-accept]');
    const cancelBtn = modal.querySelector('[data-confirm-cancel]');
    let pendingForm = null;

    const closeModal = () => {
        modal.classList.remove('is-open');
        pendingForm = null;
    };

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === 'true') {
                return;
            }

            event.preventDefault();
            pendingForm = form;
            messageEl.textContent = form.dataset.confirm;
            modal.classList.add('is-open');
        });
    });

    confirmBtn?.addEventListener('click', () => {
        const form = pendingForm;
        modal.classList.remove('is-open');
        pendingForm = null;

        if (form) {
            form.dataset.confirmed = 'true';
            form.requestSubmit();
        }
    });

    cancelBtn?.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initSidebarGroups();
    initDropdowns();
    initSidebarOffCanvas();
    initTabs();
    initConfirmForms();
});
