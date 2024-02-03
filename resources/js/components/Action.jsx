import {useState} from "react";

const Action = ({
                    name = "Action",
                    open = () => <svg className="h-3.5 w-3.5 mr-[.1rem]" fill="currentColor" viewBox="0 0 20 20"
                                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clipRule="evenodd" fillRule="evenodd"
                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"></path>
                    </svg>,
                    close = () => <svg className="w-3.5 mt-[.15rem] h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                    </svg>,
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
