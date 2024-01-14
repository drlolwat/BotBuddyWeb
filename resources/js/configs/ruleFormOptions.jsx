import DynamicSelectComponent from '../components/DynamicSelectComponent.jsx';
import {fetchAccounts, fetchScripts, fetchAccountGroups} from '../utils/fetchUtils.js';
import SelectComponent from '../components/SelectComponent.jsx';
import CreateRuleButton from '../components/CreateRuleButton.jsx';
import {Fragment} from 'react';

const className = "border-2 border-gray-300 rounded-lg mb-2 mr-2";

const ruleFormOptions = {
    modelTypeSelect: {
        name: "model_type",
        className,
        options: [{label: "Select a model type"}, {
            label: "Account",
            value: "account",
            render: () => <Fragment key="account">
                <DynamicSelectComponent
                    fetchOptions={() => fetchAccounts(() =>
                        <SelectComponent {...ruleFormOptions.eventSelect} />)} {...ruleFormOptions.modelIdSelect} />
            </Fragment>
        }, {
            label: "Account Group",
            value: "account_group",
            render: () => (
                <Fragment key="account_group">
                    <DynamicSelectComponent
                        fetchOptions={() => fetchAccountGroups(() =>
                            <SelectComponent {...ruleFormOptions.eventSelect} />)} {...ruleFormOptions.modelIdSelect} />
                </Fragment>
            )
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
                    <Fragment key="change_script">
                        <DynamicSelectComponent
                            fetchOptions={() => fetchScripts(() =>
                                <CreateRuleButton/>)} {...ruleFormOptions.actionScriptSelect} />
                    </Fragment>
                )
            },
            {
                label: "Change account group",
                value: "change_account_group",
                render: () => (
                    <Fragment key="change_account_group">
                        <DynamicSelectComponent
                            fetchOptions={() => fetchAccountGroups(() =>
                                <CreateRuleButton/>)} {...ruleFormOptions.actionAccountGroupSelect} />
                    </Fragment>
                )
            },
            {
                label: "Stop bot",
                value: "stop_bot",
                render: () => <CreateRuleButton/>,
            },
            {
                label: "Restart bot",
                value: "restart_bot",
                render: () => <CreateRuleButton/>,
            },
            {
                label: "Restart bot with script params",
                value: "restart_bot_with_script_params",
                render: () => (
                    <>
                        <input type="text" name="action_script_params"
                               className="border-2 border-gray-300 rounded-lg mb-2 mr-2" placeholder="e.g. --test=123"/>
                        <CreateRuleButton/>
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
    actionAccountGroupSelect: {
        name: "action_account_group_id",
        className,
        options: [
            {label: "Select an account group"},
        ],
    },
};

export default ruleFormOptions;
