import Action from "../Action.jsx";
import DynamicSelectComponent from "../DynamicSelect.jsx";
import {fetchScripts} from "../../utils/fetchUtils.js";
import {Fragment} from "react";

const className = "border-2 border-gray-300 rounded-lg mb-2 mr-2";

const scriptSelect = {
    name: "change_script[script_id]",
    className,
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
                            className="border-2 border-gray-300 rounded-lg mb-2 mr-2"
                            placeholder="e.g. param1 param2"
                        />
                    </Fragment>
                )
            }
        });
    }
};

const ChangeScriptAction = () => {
    return <Action
        name="Change script"
        open={() => <button>Open</button>}
        close={() => <button>Close</button>}
        content={() => <DynamicSelectComponent {...scriptSelect} />}
        className="border-b border-gray-300"
    />
}

export default ChangeScriptAction;
