import Action from "../Action.jsx";
import DynamicSelectComponent from "../DynamicSelect.jsx";
import {fetchScripts} from "../../utils/fetchUtils.js";
import {Fragment} from "react";

const selectClassNames = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 min-w-[200px] mb-2 mr-2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500";

const scriptSelect = {
    name: "change_script[script_id]",
    className: selectClassNames,
    options: [
        {label: "Select a script"},
    ],
    optionsCallback: async () => {
        const scripts = await fetchScripts();
        return scripts.map(script => {
            return {
                ...script, render: () => (
                    <Fragment>
                        <input type="hidden" name="action[]" value="change_script"/>
                        <input
                            type="text"
                            name="change_script[script_params]"
                            className={selectClassNames}
                            placeholder="e.g. param1 param2"
                        />
                    </Fragment>
                )
            }
        });
    }
};

const ChangeScriptAction = ({ className }) => {
    return <Action
        name="Change script"
        content={() => <DynamicSelectComponent className={selectClassNames} {...scriptSelect} />}
        className={className}
    />
}

export default ChangeScriptAction;
