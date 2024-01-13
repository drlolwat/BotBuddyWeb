import React, { useState, useEffect } from 'react';
import SelectComponent from './SelectComponent';

const DynamicSelectComponent = ({ fetchOptions, defaultOption = {label: "Select an option"}, ...otherProps }) => {
    const [options, setOptions] = useState([defaultOption]); // Set initial state with the default option

    useEffect(() => {
        const fetchAndSetOptions = async () => {
            try {
                const fetchedOptions = await fetchOptions();
                setOptions(prevOptions => [...prevOptions, ...fetchedOptions]);
            } catch (error) {
                console.error('Error fetching options:', error);
            }
        };

        fetchAndSetOptions();
    }, [fetchOptions]);

    return (
        <SelectComponent data={{...otherProps.data, options}} />
    );
};

export default DynamicSelectComponent;
