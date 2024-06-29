import {createRoot} from 'react-dom/client';
import ChangeScriptAction from "./components/actions/ChangeScriptAction.jsx";
import Action from "./components/Action.jsx";
import {EventProvider} from "./providers.jsx";
import Workflow from "./components/Workflow.jsx";
import Scheduler from "./components/Scheduler.jsx";
import MultiSelect from "./components/MultiSelect.jsx";
import DashboardSearchFilter from "./components/DashboardSearchFilter.jsx";
import AccountSearchFilter from "./components/AccountSearchFilter.jsx";

const scheduleApp = document.getElementById('schedule_app');
if (scheduleApp) {
    const scheduleRoot = createRoot(scheduleApp);
    scheduleRoot.render(<Scheduler />);
}

const dashboardSearchFilter = document.getElementById('dashboard_table_header');
if (dashboardSearchFilter) {
    const dashboardSearchFilterRoot = createRoot(dashboardSearchFilter);
    dashboardSearchFilterRoot.render(<DashboardSearchFilter status={true} account_group_id={true} />);
}

const accountSearchFilter = document.getElementById('account_table_header');
if (accountSearchFilter) {
    const accountSearchFilterRoot = createRoot(accountSearchFilter);
    accountSearchFilterRoot.render(<AccountSearchFilter status={true} account_group_id={true} />);
}

const scheduleMultiSelect = document.getElementById('schedule_multiselect');
if (scheduleMultiSelect) {
    const scheduleMultiSelectRoot = createRoot(scheduleMultiSelect);
    scheduleMultiSelectRoot.render(<MultiSelect />);
}

const app = document.getElementById('app');
if (app) {
    const root = createRoot(app);

    const actions = {
        changeScript: {
            jsx: () => <ChangeScriptAction/>,
            events: ["script_complete"],
        },
        stopBot: {
            jsx: () => <Action name="Stop bot" className="border-b border-gray-300" content={() => <div>The bot will be stopped</div>}/>,
            events: ["script_complete"],
        },
        restartBot: {
            jsx: () => <Action name="Stop bot" className="border-b border-gray-300" content={() => <div>The bot will be restarted</div>}/>,
            events: ["script_complete"],
        }
    };

    root.render(
        <EventProvider>
            <Workflow />
        </EventProvider>
    );
}

if (newLayout) {
    document.addEventListener('DOMContentLoaded', function() {
        const navToggle = document.querySelectorAll('[data-drawer-toggle="drawer-navigation"]');
        navToggle.forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.drawerTarget);
                target.classList.toggle('translate-x-0');
                target.classList.toggle('-translate-x-full');
            });
        });

        const subNavToggles = document.querySelectorAll('[data-collapse-toggle]');
        subNavToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const targetId = this.dataset.collapseToggle;
                const target = document.getElementById(targetId);
                target.classList.toggle('hidden');
            });
        });
    });

    function closeAllDropdowns() {
        dropdownToggles.forEach(function(toggle) {
            const dropdownMenuId = toggle.getAttribute('data-dropdown-toggle');
            const dropdownMenu = document.getElementById(dropdownMenuId);
            if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
                dropdownMenu.classList.add('hidden');
                dropdownMenu.classList.remove('absolute');
            }
        });
    }

    const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
    if (dropdownToggles) {
        dropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(event) {
                event.stopPropagation();
                const dropdownMenuId = toggle.getAttribute('data-dropdown-toggle');
                const dropdownMenu = document.getElementById(dropdownMenuId);
                if (dropdownMenu) {
                    if (dropdownMenu.classList.contains('hidden')) {
                        closeAllDropdowns();
                        dropdownMenu.classList.remove('hidden');
                        dropdownMenu.classList.add('absolute');
                    } else {
                        dropdownMenu.classList.add('hidden');
                        dropdownMenu.classList.remove('absolute');
                    }
                }
            });
        });

        document.addEventListener('click', function(event) {
            let targetElement = event.target;
            let isDropdown = targetElement.matches('[data-dropdown-toggle]') || targetElement.closest('[data-dropdown-toggle]');
            if (!isDropdown) {
                closeAllDropdowns();
            }
        });
    }
}
