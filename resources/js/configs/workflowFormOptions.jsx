import DynamicSelect from '../components/DynamicSelect.jsx';
import {fetchAccounts, fetchScripts, fetchAccountGroups, fetchWorkflowEvents} from '../utils/fetchUtils.js';
import Select from '../components/Select.jsx';
import CreateWorkflowButton from '../components/CreateWorkflowButton.jsx';
import {Fragment} from 'react';
import CallbackRunner from "../components/CallbackRunner.jsx";
import Alert from "../components/Alert.jsx";

// const className = "border-2 border-gray-300 rounded-lg mb-2 mr-2";
const className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 min-w-[200px] mb-2 mr-2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500";

const workflowFormOptions = {
    modelTypeSelect: {
        name: "model_type",
        className,
        options: [{label: "Select a model type"}, {
            label: "Account",
            value: "account",
            render: (parent, callback) => {
                return <DynamicSelect
                    parent={parent} callback={callback}
                    {...workflowFormOptions.accountIdSelect}
                />
            }
        }, {
            label: "Account Group",
            value: "account_group",
            render: (parent, callback) => (
                <Fragment key="account_group">
                    <DynamicSelect
                        parent={parent} callback={callback}
                        {...workflowFormOptions.accountGroupIdSelect}
                    />
                </Fragment>
            )
        }],
    },
    accountIdSelect: {
        name: "model_id",
        className,
        options: [{label: "Select a model"}],
        optionsCallback: async () => {
            const accounts = await fetchAccounts();
            const workflowEvents =  await fetchWorkflowEvents();
            let eventSelect = {...workflowFormOptions.eventSelect};
            eventSelect.options = eventSelect.options.filter((event) => {
                return workflowEvents.includes(event.value) || event.value === undefined;
            });
            return accounts.map(script => {
                return {
                    label: script.label,
                    value: script.value,
                    render: (parent, callback) => {
                        return <Select
                            parent={parent} callback={callback}
                            {...eventSelect}
                        />
                    }
                }
            });
        }
    },
    accountGroupIdSelect: {
        name: "model_id",
        className,
        options: [{label: "Select a model"}],
        optionsCallback: async () => {
            const accountGroups = await fetchAccountGroups();
            const workflowEvents =  await fetchWorkflowEvents();
            let eventSelect = {...workflowFormOptions.eventSelect};
            eventSelect.options = eventSelect.options.filter((event) => {
                return workflowEvents.includes(event.value) || event.value === undefined;
            });
            return accountGroups.map(script => {
                return {
                    label: script.label,
                    value: script.value,
                    render: (parent, callback) => {
                        return <Select parent={parent} callback={callback} {...eventSelect} />
                    }
                }
            });
        }
    },
    eventSelect: {
        name: "event",
        className,
        options: [
            {label: "Select an event"},
            {
                label: "Completes script",
                value: "script_complete",
                render: (parent, callback) => <Fragment key="script_complete">
                    <DynamicSelect
                        parent={parent} callback={callback}
                        {...workflowFormOptions.eventScriptSelect}
                    />
                </Fragment>,
            },
            {
                label: "Proxy is blocked",
                value: "proxy_blocked",
                render: (parent, callback) => <CallbackRunner
                    parent={parent}
                    callback={callback}
                    name="event"
                    value="proxy_blocked"
                />,
            },
            {
                label: "Is temp banned",
                value: "temp_banned",
                render: (parent, callback) => <CallbackRunner
                    parent={parent}
                    callback={callback}
                    name="event"
                    value="temp_banned"
                 />,
            },
            {
                label: "Is perm banned",
                value: "perm_banned",
                render: (parent, callback) => <CallbackRunner
                    parent={parent}
                    callback={callback}
                    name="event"
                    value="perm_banned"
                />,
            },
        ],
    },
    eventScriptSelect: {
        name: "event_script_id",
        className,
        options: [
            {label: "Select a script"},
        ],
        optionsCallback: async () => {
            const scripts = await fetchScripts();
            return scripts.map(script => {
                return {
                    ...script, render: (parent, callback) => {
                        return null;
                    }
                }
            });
        }
    },
    // actionSelect: {
    //     name: "action",
    //     className,
    //     options: [
    //         {label: "Select an action"},
    //         {
    //             label: "Change script",
    //             value: "change_script",
    //             // render: (props) => (
    //             //     <Fragment key="change_script">
    //             //         <DynamicSelectComponent parent={parent} callback={callback}
    //             //                                 fetchOptions={() => fetchScripts(() =>
    //             //                                     <>
    //             //                                         <input type="text" name="action_script_params"
    //             //                                                className="border-2 border-gray-300 rounded-lg mb-2 mr-2"
    //             //                                                placeholder="e.g. param1 param2"/>
    //             //                                         <CreateWorkflowButton/>
    //             //                                     </>)} {...workflowFormOptions.actionScriptSelect} />
    //             //     </Fragment>
    //             // )
    //         },
    //         {
    //             label: "Change account group",
    //             value: "change_account_group",
    //             // render: (parent,callback) => (
    //             //     <Fragment key="change_account_group">
    //             //         <DynamicSelectComponent parent={parent} callback={callback}
    //             //                                 fetchOptions={() => fetchAccountGroups(() =>
    //             //                                     <CreateWorkflowButton/>)} {...workflowFormOptions.actionAccountGroupSelect} />
    //             //     </Fragment>
    //             // )
    //         },
    //         {
    //             label: "Stop bot",
    //             value: "stop_bot",
    //             render: (parent,callback) => <CreateWorkflowButton/>,
    //         },
    //         {
    //             label: "Restart bot",
    //             value: "restart_bot",
    //             render: (parent,callback) => <CreateWorkflowButton/>,
    //         },
    //         {
    //             label: "Restart bot with script params",
    //             value: "restart_bot_with_script_params",
    //             render: (parent,callback) => (
    //                 <>
    //                     <input type="text" name="action_script_params"
    //                            className="border-2 border-gray-300 rounded-lg mb-2 mr-2"
    //                            placeholder="e.g. param1 param2"/>
    //                     <CreateWorkflowButton/>
    //                 </>
    //             ),
    //         },
    //     ],
    // },
    actionScriptSelect: {
        name: "action_script_id",
        className,
        options: [
            {label: "Select a script"},
        ],
    },
    actionAccountGroupSelect: {
        name: "change_account_group[account_group_id]",
        className,
        options: [
            {label: "Select an account group"},
        ],
        optionsCallback: async () => {
            const accountGroups = await fetchAccountGroups();
            return accountGroups.map(script => {
                return {
                    label: script.label,
                    value: script.value,
                    render: (parent, callback) => {
                        return null;
                    }
                }
            });
        }
    },
    actionReplenishWithAccountGroupSelect: {
        name: "stop_and_replenish_with[account_group_id]",
        className,
        options: [
            {label: "Select an account group"},
        ],
        optionsCallback: async () => {
            const accountGroups = await fetchAccountGroups();
            return accountGroups.map(script => {
                return {
                    label: script.label,
                    value: script.value,
                    render: (parent, callback) => {
                        return <span className="ml-2">
                            <select name="change_proxy[type]" className={className}>
                                           <option value="existing">Use existing proxy configuration</option>
                                           <option value="random">Random proxy from account group</option>
                                           <option value="random_unused">Random unused proxy from account group</option>
                                       </select>
                                       <Alert message={"Note: If selecting a random proxy and there is none available, the replenishment account will not be started."}/>
                            {/*<span className="mr-2">Random proxy?</span>*/}
                            {/*<input type="checkbox" name="stop_and_replenish_with[random_proxy]"*/}
                            {/*       className="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>*/}
                        </span>;
                    }
                }
            });
        }
    },
};

export default workflowFormOptions;
