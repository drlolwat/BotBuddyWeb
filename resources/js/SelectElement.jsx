import React from 'react';

const SelectElement = ({value, options, onChange, name, defaultText, className}) => (
    <select value={value} onChange={onChange} name={name} className={className}>
        <option value="">{defaultText || 'Select an option'}</option>
        {options.map(option => (
            <option key={option.value} value={option.value}>{option.label}</option>
        ))}
    </select>
);

export default SelectElement;
