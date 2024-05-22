import {Fragment, useContext} from "react";
import {EventContext} from "../context.jsx";
import CreateWorkflowButton from "./CreateWorkflowButton.jsx";
import ChangeScriptAction from "./actions/ChangeScriptAction.jsx";
import Action from "./Action.jsx";
import Select from "./Select.jsx";
import workflowFormOptions from "../configs/workflowFormOptions.jsx";
import DynamicSelect from "./DynamicSelect.jsx";
import {fetchAccountGroups, fetchProxyGroups} from "../utils/fetchUtils.js";
import Alert from "./Alert.jsx";

// const className = "border-b border-gray-300";
const textClassNames = "text-gray-900 dark:text-white";
const className = `border-b border-gray-200 dark:border-gray-600 ${textClassNames}`;
const selectClassNames = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 min-w-[200px] mb-2 mr-2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500";

const Workflow = () => {
    const {event, updateEvent} = useContext(EventContext);

    const actions = {
        testScript: {
            jsx: () => <Action
                name="Stop and replenish with"
                className={className}
                content={() => (
                    <>
                        <input type="hidden" name="action[]" value="stop_and_replenish_with"/>
                        <DynamicSelect
                            {...workflowFormOptions.actionReplenishWithAccountGroupSelect}
                        />
                    </>
                )}/>,
            events: ["script_complete"],
        },
        changeScript: {
            jsx: () => <ChangeScriptAction className={className} />,
            events: ["script_complete"],
        },
        stopBot: {
            jsx: () => <Action name="Stop bot" className={className}
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="stop_bot"/>
                                       <Alert message={"The bot will be stopped"}/>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        restartBot: {
            jsx: () => <Action name="Restart bot" className={className}
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="restart_bot"/>
                                        <Alert message={"The bot will be restarted"}/>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        restartBotWithScriptParams: {
            jsx: () => <Action name="Restart bot with script params" className={className}
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="restart_bot_with_script_params"/>
                                       <input type="text" name="restart_bot_with_script_params[script_params]"
                                              className={selectClassNames}
                                              placeholder="e.g. param1 param2"/>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        changeAccountGroup: {
            jsx: () => <Action name="Change account group" className={className}
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="change_account_group"/>
                                       <DynamicSelect
                                           {...workflowFormOptions.actionAccountGroupSelect}
                                       />
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        changeProxy: {
            jsx: () => <Action name="Change proxy" className={className}
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="change_proxy"/>
                                       <select name="change_proxy[type]" className={selectClassNames}>
                                           <option value="random">Random proxy from proxy group</option>
                                           <option value="random_unused">Random unused proxy from proxy group</option>
                                       </select>
                                       <DynamicSelect {...{
                                           name: "change_proxy[proxy_group_id]",
                                           className: selectClassNames,
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
                                       <Alert message={"Note: The account will not be restarted if there are no other proxies available."}/>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        removeProxy: {
            jsx: () => <Action name="Remove proxy" className={className}
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="remove_proxy"/>
                                       <Alert message={"The proxy will be removed from the account"}/>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
    };

    const hasSelected = (obj, name, value, selectedValue, defaultValue) => {
        if (!obj) return false;
        if (selectedValue && defaultValue) {
            if (obj.parentName === name && obj.parentSelectedValue === value && selectedValue === defaultValue) return false;
            if (obj.parentName === name && obj.parentSelectedValue === value && !(selectedValue === defaultValue)) return true;
            if (obj.parentName === name && !(obj.parentSelectedValue === value)) return false;
        } else {
            if (obj.parentName === name && obj.parentSelectedValue === value) return true;
        }
        return hasSelected(obj.parent, name, value);
    }

    // todo: make this configurable
    const callback = (parent, selectedValue, defaultValue) => {
        if (hasSelected(parent, "event", "script_complete", selectedValue, defaultValue)
            || hasSelected(parent, "event", "temp_banned")
            || hasSelected(parent, "event", "perm_banned")
            || hasSelected(parent, "event", "proxy_blocked")
            || hasSelected(parent, "event", "locked")
            || (selectedValue === "stat_goal" && defaultValue === "Select an event")
        ) {
            updateEvent("script_complete");
        } else {
            updateEvent(null);
        }
    }

    return (
        <>
            <DynamicSelect {...workflowFormOptions.accountGroupIdSelect} callback={callback}/>
            <div className={`py-2 font-bold ${textClassNames}`}>Actions</div>
            {!event && <div className={textClassNames}>Select an event to see the possible actions</div>}
            <div className="grid gap-2">
                {Object.entries(actions).map(([key, action]) => {
                    if (action.events.includes(event)) {
                        return <div key={key}>{action.jsx()}</div>;
                    }
                    return null;
                })}
                <div className="flex justify-end">
                    {event && <CreateWorkflowButton/>}
                </div>

            </div>
        </>
    )
}

export default Workflow;
