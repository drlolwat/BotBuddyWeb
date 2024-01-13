import React, {useState} from 'react';

const SelectComponent = ({name, className, options = [{label: "Select an option"}], defaultValue}) => {
    const [selectedValue, setSelectedValue] = useState(defaultValue || options[0].value || options[0].label);
    const handleSelectionChange = (event) => setSelectedValue(event.target.value);
    const selectedOption = options.find(option => (option.value || option.label).toString() === selectedValue.toString());

    return (
        <>
            <select {...(name ? {name: name} : {})} {...(className ? {className: className} : {})} onChange={handleSelectionChange}>
                {options.map(option => (
                    <option key={option.value} value={option.value} selected={selectedValue === option.value}>{option.label}</option>
                ))}
            </select>
            {selectedOption && selectedOption.render && selectedOption.render()}
        </>
    );
};

export default SelectComponent;
