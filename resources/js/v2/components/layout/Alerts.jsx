import React, {Fragment} from 'react';

const Alerts = ({ flash, errors }) => {
    return (
        <Fragment>
            {errors && Object.keys(errors).length > 0 && (
                <div
                    id="alert-border-2"
                    className="flex p-4 mb-4 text-sm text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800"
                    role="alert"
                >
                    <svg
                        className="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <div>
                        <span className="font-medium">Error:</span>
                        <ul className="mt-1.5 list-disc list-inside">
                            {Object.values(errors).flat().map((error, index) => (
                                <li key={index}>{error}</li>
                            ))}
                        </ul>
                    </div>
                </div>
            )}

            {flash.warning && (
                <div
                    id="alert-border-3"
                    className="flex items-center p-4 mb-4 text-orange-800 border-t-4 border-orange-300 bg-orange-50 dark:text-orange-400 dark:bg-gray-800 dark:border-orange-800"
                    role="alert"
                >
                    <svg
                        className="flex-shrink-0 w-4 h-4"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <div className="ms-3 text-sm font-medium">{flash.warning}</div>
                </div>
            )}

            {flash.status &&
                flash.status !== 'two-factor-authentication-confirmed' &&
                flash.status !== 'two-factor-authentication-enabled' && (
                    <div
                        id="alert-border-3"
                        className="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50 dark:text-green-400 dark:bg-gray-800 dark:border-green-800"
                        role="alert"
                    >
                        <svg
                            className="flex-shrink-0 w-4 h-4"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <div className="ms-3 text-sm font-medium">{flash.status}</div>
                    </div>
                )}
        </Fragment>
    );
};

export default Alerts;
