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
