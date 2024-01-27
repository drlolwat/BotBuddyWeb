import {createRoot} from 'react-dom/client';
import ChangeScriptAction from "./components/actions/ChangeScriptAction.jsx";
import Action from "./components/Action.jsx";
import {EventProvider} from "./providers.jsx";
import Workflow from "./components/Workflow.jsx";

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
}
