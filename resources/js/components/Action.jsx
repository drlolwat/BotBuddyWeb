import {useState} from "react";

const Action = ({
                    name = "Action",
                    open = () => <button>Open</button>,
                    close = () => <button>Close</button>,
                    content = () => <div>Content</div>,
                    className
                }) => {

    const [added, setAdded] = useState(false);

    return (
        <div className={className}>
            <div className="flex">
                <div className="flex-grow">
                    <div>{name}</div>
                </div>
                {!added && <div onClick={() => setAdded(true)}>{open()}</div>}
                {added && <div onClick={() => setAdded(false)}>{close()}</div>}
            </div>
            <div className="py-2">
                {added && content()}
            </div>
        </div>
    )
}

export default Action;
