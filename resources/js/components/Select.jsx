import {Fragment, useState} from "react";

const Select = ({
                    name,
                    className,
                    options,
                    callback,
                    parent = {parentName: "", parentSelectedValue: "", parent: {}}
                }) => {
    const defaultValue = options[0].value || options[0].label;
    const [selectedValue, setSelectedValue] = useState(defaultValue);
    const newParent = () => ({parentName: name, parentSelectedValue: selectedValue, parent: {...parent}});

    const hasSelected = (obj, name, value) => {
        if (!obj) return false;
        if (obj.parentName === name && !(obj.parentSelectedValue === value)) return false;
        if (obj.parentName === name && obj.parentSelectedValue === value) return true;

        return hasSelected(obj.parent, name, value);
    }

    const onChange = async (e) => {
        const newValue = e.target.value;
        setSelectedValue(newValue);

        if (callback) {
            callback(parent, newValue, defaultValue);
        }
    }

    return (
        <>
            <select
                className={className}
                name={name}
                onChange={onChange}
            >
                {options.length > 0 && options.map(({label, value}, index) => <option key={`${name}_${index}`}
                                                                                      value={value}>{label}</option>)}
            </select>
            {options.length > 0 && options.map(({
                                                    value,
                                                    render
                                                }, index) => selectedValue === value && render &&
                <Fragment key={`${name}_${index}_render`}>{render(newParent(), callback)}</Fragment>)}
        </>
    )
}

export default Select;
