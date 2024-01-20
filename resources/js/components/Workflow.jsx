import {Fragment, useContext} from "react";
import {EventContext} from "../context.jsx";
import CreateWorkflowButton from "./CreateWorkflowButton.jsx";
import ChangeScriptAction from "./actions/ChangeScriptAction.jsx";
import Action from "./Action.jsx";
import Select from "./Select.jsx";
import workflowFormOptions from "../configs/workflowFormOptions.jsx";
import DynamicSelect from "./DynamicSelect.jsx";
import {fetchAccountGroups} from "../utils/fetchUtils.js";

const Workflow = () => {
    const {event, updateEvent} = useContext(EventContext);

    const actions = {
        changeScript: {
            jsx: () => <ChangeScriptAction/>,
            events: ["script_complete"],
        },
        stopBot: {
            jsx: () => <Action name="Stop bot" className="border-b border-gray-300"
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="stop_bot"/>
                                       <div>The bot will be stopped</div>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        restartBot: {
            jsx: () => <Action name="Restart bot" className="border-b border-gray-300"
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="restart_bot"/>
                                       <div>The bot will be restarted</div>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        restartBotWithScriptParams: {
            jsx: () => <Action name="Restart bot with script params" className="border-b border-gray-300"
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="restart_bot_with_script_params"/>
                                       <input type="text" name="restart_bot_with_script_params[script_params]"
                                              className="border-2 border-gray-300 rounded-lg mb-2 mr-2"
                                              placeholder="e.g. param1 param2"/>
                                   </>
                               )}/>,
            events: ["script_complete"],
        },
        changeAccountGroup: {
            jsx: () => <Action name="Change account group" className="border-b border-gray-300"
                               content={() => (
                                   <>
                                       <input type="hidden" name="action[]" value="change_account_group"/>
                                       <DynamicSelect
                                           {...workflowFormOptions.actionAccountGroupSelect}
                                       />
                                   </>
                               )}/>,
            events: ["script_complete"],
        }
    };

    const hasSelected = (obj, name, value, selectedValue, defaultValue) => {
        console.log(obj, name, value, selectedValue, defaultValue);
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
        ) {
            updateEvent("script_complete");
        } else {
            updateEvent(null);
        }
    }

    return (
        <>
            <Select {...workflowFormOptions.modelTypeSelect} callback={callback}/>
            <div className="py-2 font-bold">Actions</div>
            {!event && <div>Select an event to see the possible actions</div>}
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
