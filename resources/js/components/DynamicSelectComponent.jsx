import React, {useEffect, useState} from 'react';
import SelectComponent from './SelectComponent.jsx';

const DynamicSelectComponent = ({name, className, options = [{label: "Select an option"}], defaultValue, fetchOptions}) => {

    const [mergedOptions, setMergedOptions] = useState([...options]);

    useEffect(() => {
        const fetchAndSetOptions = async () => {
            try {
                const fetchedOptions = await fetchOptions();
                setMergedOptions(prevOptions => [...prevOptions, ...fetchedOptions]);
            } catch (error) {
                console.error('Error fetching options:', error);
            }
        };

        fetchAndSetOptions();
    }, []);

    return (
        <SelectComponent name={name} className={className} options={mergedOptions} defaultValue={defaultValue} />
    );
};

export default DynamicSelectComponent;
