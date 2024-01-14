import DynamicSelectComponent from '../components/DynamicSelectComponent.jsx';
import {fetchAccounts, fetchScripts} from '../utils/fetchUtils.js';
import SelectComponent from '../components/SelectComponent.jsx';
import createRuleButton from '../components/CreateRuleButton.jsx';
import CreateRuleButton from "../components/CreateRuleButton.jsx";

const className ="border-2 border-gray-300 rounded-lg mb-2 mr-2";

const ruleFormOptions = {
    modelTypeSelect: {
        name: "model_type",
        className,
        options: [{label: "Select a model type"}, {
            label: "Account",
            value: "account",
            render: () => <DynamicSelectComponent
                fetchOptions={() => fetchAccounts(() =>
                    <SelectComponent {...ruleFormOptions.eventSelect} />)} {...ruleFormOptions.modelIdSelect} />
        }],
    },
    modelIdSelect: {
        name: "model_id",
        className,
        options: [{label: "Select a model"}],
    },
    eventSelect: {
        name: "event",
        className,
        options: [
            {label: "Select an event"},
            {
                label: "Completes script",
                value: "script_complete",
                render: () => <div>
                    <DynamicSelectComponent
                        fetchOptions={() => fetchScripts(() =>
                            <SelectComponent {...ruleFormOptions.actionSelect} />)} {...ruleFormOptions.eventScriptSelect}
                    />
                </div>
            },
        ],
    },
    eventScriptSelect: {
        name: "event_script_id",
        className,
        options: [
            {label: "Select a script"},
        ],
    },
    actionSelect: {
        name: "action",
        className,
        options: [
            {label: "Select an action"},
            {
                label: "Change script",
                value: "change_script",
                render: () => (
                    <DynamicSelectComponent
                        fetchOptions={() => fetchScripts(() => <CreateRuleButton />)} {...ruleFormOptions.actionScriptSelect} />
                )
            },
            {
                label: "Stop bot",
                value: "stop_bot",
                render: () => <CreateRuleButton />,
            },
            {
                label: "Restart bot",
                value: "restart_bot",
                render: () => <CreateRuleButton />,
            },
            {
                label: "Restart bot with script params",
                value: "restart_bot_with_script_params",
                render: () => (
                    <>
                        <input type="text" name="action_script_params" className="border-2 border-gray-300 rounded-lg mb-2 mr-2" placeholder="e.g. --test=123" />
                        <CreateRuleButton />
                    </>
                ),
            },
        ],
    },
    actionScriptSelect: {
        name: "action_script_id",
        className,
        options: [
            {label: "Select a script"},
        ],
    },
};

export default ruleFormOptions;
