import {useEffect, useState} from "react";
import Select from "./Select.jsx";

const DynamicSelect = ({optionsCallback, ...props}) => {
    const [newOptions, setNewOptions] = useState([]);

    useEffect(() => {
        async function fetchOptions() {
            if (optionsCallback) {
                const additionalOptions = await optionsCallback();
                setNewOptions(additionalOptions);
            }
        }

        fetchOptions();
    }, [optionsCallback]);

    const combinedOptions = [...props.options, ...newOptions];

    return <Select {...props} options={combinedOptions}/>
}

export default DynamicSelect;
