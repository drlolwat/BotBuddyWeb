import React, {useMemo, useState} from 'react';
import SelectElement from './SelectElement.jsx';

const SelectGroup = ({data}) => {
    const getInitialOptions = useMemo(() => {
        return Object.keys(data.options).map(key => ({value: key, label: key, name: data.options[key].name}));
    }, [data.options]);

    const getNextRef = (index) => {
        let ref = data.options;
        for (let i = 0; i < index; i++) {
            ref = ref[selectStates[i].value].options;
            if (!ref) return null;
        }
        return ref;
    }

    const getNextOptions = (value, index) => {
        const ref = getNextRef(index);
        if (!ref) return [];

        const nextLevelData = ref[value];
        if (typeof nextLevelData === 'object' && nextLevelData !== null) {
            return Object.keys(nextLevelData.options).map(key => ({value: key, label: key, name: nextLevelData.name}));
        } else if (typeof nextLevelData === 'function') {
            return [];
        }

        return [];
    }

    const getNextDefaultText = (value, index) => {
        const ref = getNextRef(index);
        if (!ref) return "";

        const nextLevelData = ref[value];
        if (typeof nextLevelData === 'object' && nextLevelData !== null) {
            return nextLevelData.defaultText;
        } else if (typeof nextLevelData === 'function') {
            return "";
        }

        return "";
    }

    const getNextClassName = (value, index) => {
        const ref = getNextRef(index);
        if (!ref) return "";

        const nextLevelData = ref[value];
        if (typeof nextLevelData === 'object' && nextLevelData !== null) {
            return nextLevelData.className;
        } else if (typeof nextLevelData === 'function') {
            return "";
        }

        return "";
    }

    const handleChange = (value, index) => {
        let newSelectStates = [...selectStates.slice(0, index + 1)];
        newSelectStates[index] = { ...newSelectStates[index], value: value, jsx: null };

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

    const initialSelectState = useMemo(() => ({
        value: '',
        options: getInitialOptions,
        jsx: null,
        name: data.name,
        defaultText: data.defaultText,
        className: data.className,
    }), [getInitialOptions, data.name, data.defaultText, data.className]);

    const [selectStates, setSelectStates] = useState([initialSelectState]);


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
                    {selectState.jsx && <>{selectState.jsx}</>}
                </div>
            ))}
        </>
    );
};

export default SelectGroup;
