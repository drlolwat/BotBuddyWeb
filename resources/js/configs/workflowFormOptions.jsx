import DynamicSelect from '../components/DynamicSelect.jsx';
import {
    fetchAccounts,
    fetchScripts,
    fetchAccountGroups,
    fetchWorkflowEvents,
    fetchProxyGroups
} from '../utils/fetchUtils.js';
import Select from '../components/Select.jsx';
import CreateWorkflowButton from '../components/CreateWorkflowButton.jsx';
import {Fragment} from 'react';
import CallbackRunner from "../components/CallbackRunner.jsx";
import Alert from "../components/Alert.jsx";
import StatGoalForm from "../components/StatGoalForm.jsx";

// const className = "border-2 border-gray-300 rounded-lg mb-2 mr-2";
const className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 min-w-[200px] mb-2 mr-2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500";

const workflowFormOptions = {
    modelTypeSelect: {
        name: "model_type",
        className,
        options: [{label: "Select a model type"},
            // {
            //     label: "Account",
            //     value: "account",
            //     render: (parent, callback) => (
            //         <DynamicSelect
            //             parent={parent} callback={callback}
            //             {...workflowFormOptions.accountIdSelect}
            //         />
            //     )
            // },
            {
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
            }
            ],
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
        options: [{label: "Select an account group"}],
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
                    value   ="temp_banned"
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
            {
                label: "Reaches stat goal",
                value: "stat_goal",
                render: (parent, callback) => <Fragment key="stat_goal">
                    <div>
                        <StatGoalForm />
                    </div>
                </Fragment>,
            },
            {
                label: "Is locked",
                value: "locked",
                render: (parent, callback) => <CallbackRunner
                    parent={parent}
                    callback={callback}
                    name="event"
                    value="locked"
                />,
            }
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
                            {/*<select name="stop_and_replenish_with[type]" className={className}>*/}
                            {/*               <option>Select an option</option>*/}
                            {/*               <option value="existing">Do not change proxy</option>*/}
                            {/*               <option value="random">Random proxy from account group</option>*/}
                            {/*               <option value="random_unused">Random unused proxy from account group</option>*/}
                            {/*           </select>*/}
                            <DynamicSelect {...{
                                name: "stop_and_replenish_with[type]",
                                className,
                                options: [
                                    {label: "Select a proxy option"},
                                    {label: "Do not change proxy", value: "existing", render: (parent, callback) => (
                                        <input type="hidden" name="stop_and_replenish_with[proxy_group_id]" />
                                        )},
                                    {label: "Use proxy from account that triggered event", value: "triggered", render: (parent, callback) => (
                                            <input type="hidden" name="stop_and_replenish_with[proxy_group_id]" />
                                        )},
                                    {label: "Random proxy from proxy group", value: "random", render: (parent, callback) => (
                                            <DynamicSelect {...{
                                                name: "stop_and_replenish_with[proxy_group_id]",
                                                className,
                                                options: [
                                                    {label: "Select a proxy group"},
                                                ],
                                                optionsCallback: async () => {
                                                    const accountGroups = await fetchProxyGroups();
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
                                            }} />
                                        )},
                                    {label: "Random unused proxy from proxy group", value: "random_unused", render: (parent, callback) => (
                                            <DynamicSelect {...{
                                                name: "stop_and_replenish_with[proxy_group_id]",
                                                className,
                                                options: [
                                                    {label: "Select a proxy group"},
                                                ],
                                                optionsCallback: async () => {
                                                    const accountGroups = await fetchProxyGroups();
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
                                            }} />
                                        )},
                                ],
                            }} />
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
