import React, {Fragment, useState} from 'react';

const SelectGroup = ({data}) => {
    const [selectStates, setSelectStates] = useState([{
        value: '',
        options: getInitialOptions(),
        jsx: null,
        name: data.name,
        defaultText: data.defaultText,
        className: data.className,
    }]);

    function getInitialOptions() {
        return Object.keys(data.options).map(key => ({value: key, label: key, name: data.options[key].name}));
    }

    function getNextOptions(currentValue, level) {
        let ref = data.options;
        for (let i = 0; i < level; i++) {
            ref = ref[selectStates[i].value].options;
            if (!ref) return [];
        }

        const nextLevelData = ref[currentValue];
        if (typeof nextLevelData === 'object' && nextLevelData !== null) {
            return Object.keys(nextLevelData.options).map(key => ({value: key, label: key, name: nextLevelData.name}));
        } else if (typeof nextLevelData === 'function') {
            return [];
        }

        return [];
    }

    function getNextDefaultText(currentValue, level) {
        let ref = data.options;
        for (let i = 0; i < level; i++) {
            ref = ref[selectStates[i].value].options;
            if (!ref) return "NO DEFAULT TEXT SET?";
        }

        const nextLevelData = ref[currentValue];
        if (typeof nextLevelData === 'object' && nextLevelData !== null) {
            return nextLevelData.defaultText;
        } else if (typeof nextLevelData === 'function') {
            return "";
        }

        return "";
    }

    function getNextClassName(currentValue, level) {
        let ref = data.options;
        for (let i = 0; i < level; i++) {
            ref = ref[selectStates[i].value].options;
            if (!ref) return "NO DEFAULT CLASSNAME SET?";
        }

        const nextLevelData = ref[currentValue];
        if (typeof nextLevelData === 'object' && nextLevelData !== null) {
            return nextLevelData.className;
        } else if (typeof nextLevelData === 'function') {
            return "";
        }

        return "";
    }

    const handleChange = (value, index) => {
        let newSelectStates = selectStates.slice(0, index + 1);
        newSelectStates[index].value = value;
        newSelectStates[index].jsx = null;

        if (value === '') {
            newSelectStates = newSelectStates.slice(0, index + 1);
        } else {
            let ref = data.options;
            for (let i = 0; i < index; i++) {
                ref = ref[newSelectStates[i].value].options;
            }

            if (typeof ref[value] === 'function') {
                newSelectStates[index].jsx = ref[value]();
            } else {
                ref = ref[value].options;
                const nextOptions = getNextOptions(value, index);
                if (value && nextOptions.length > 0) {
                    newSelectStates.push({
                        value: '',
                        options: nextOptions,
                        jsx: null,
                        name: nextOptions[0].name,
                        defaultText: getNextDefaultText(value, index),
                        className: getNextClassName(value, index)
                    });
                }
            }
        }

        setSelectStates(newSelectStates);
    };


    return (
        <>
            {selectStates.map((selectState, index) => (
                <div key={index}>
                    <SelectElement
                        value={selectState.value}
                        options={selectState.options}
                        onChange={(e) => handleChange(e.target.value, index)}
                        name={selectState.name}
                        defaultText={selectState.defaultText}
                        className={selectState.className}
                    />
                    {selectState.jsx && <Fragment>{selectState.jsx}</Fragment>}
                </div>
            ))}
        </>
    );
};

const SelectElement = ({value, options, onChange, name, defaultText, className}) => (
    <select value={value} onChange={onChange} name={name} className={className}>
        <option value="">{defaultText || 'Select an option'}</option>
        {options.map(option => (
            <option key={option.value} value={option.value}>{option.label}</option>
        ))}
    </select>
);

export default SelectGroup;
