import {createRoot} from 'react-dom/client';
import React from 'react';
import SelectComponent from './SelectComponent.jsx';

(async () => {
    const triggerScriptCompleteActionSelect = {
        name: "action",
        className: "border-2 border-gray-300 rounded-lg mb-2 mr-2",
        options: [
            {label: "Select an action"},
            {label: "Change script", value: "change_script", render: () => <SelectComponent data={actionScriptSelect} />},
        ],
    }

    const accountTriggerSelect = {
        name: "event",
        className: "border-2 border-gray-300 rounded-lg mb-2 mr-2",
        options: [
            {label: "Select an event"},
            {label: "Completes script", value: "script_complete", render: () => <div><SelectComponent data={eventScriptSelect} /></div>},
        ],
    }
    let accountSelect = {
        name: "model_id",
        className: "border-2 border-gray-300 rounded-lg mb-2 mr-2",
        options: [
            {label: "Select an account"},
        ],
    };

    let actionScriptSelect = {
        name: "action_script_id",
        className: "border-2 border-gray-300 rounded-lg mb-2 mr-2",
        options: [
            {label: "Select a script"},
        ],
    };

    let eventScriptSelect = {
        name: "event_script_id",
        className: "border-2 border-gray-300 rounded-lg mb-2 mr-2",
        options: [
            {label: "Select a script"},
        ],
    };

    const modelTypeSelect = {
        name: "model_type",
        className: "border-2 border-gray-300 rounded-lg mb-2 mr-2",
        options: [
            {label: "Select a model"},
            {label: "Account", value: "account", render: () => <SelectComponent data={accountSelect}/>},
        ],
    };

    const accountsResponse = await fetch('/api/user/account');
    const accounts = await accountsResponse.json();

    const scriptsResponse = await fetch('/api/user/script');
    const scripts = await scriptsResponse.json();

    for (let i = 0; i < accounts.length; i++) {
        accountSelect.options.push({label: accounts[i].email, value: accounts[i].id, render: () => <SelectComponent data={accountTriggerSelect} />});
    }

    for (let i = 0; i < scripts.length; i++) {
        console.log(scripts[i]);
        eventScriptSelect.options.push({label: scripts[i].name, value: scripts[i].id, render: () => <SelectComponent data={triggerScriptCompleteActionSelect} />});
        actionScriptSelect.options.push({label: scripts[i].name, value: scripts[i].id, render: () => <button className="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 mb-2">Submit</button>});
    }

    const app = document.getElementById('app');
    if (app) {
        const root = createRoot(app);
        root.render(<>
            <div>When</div>
            <SelectComponent data={modelTypeSelect} />
        </>);
    }
})();

const toggle = document.getElementById('toggle');
const nav = document.querySelector('nav');

if (toggle && nav) {
    toggle.addEventListener('click', function () {
        nav.classList.toggle('hidden');
    });
}
