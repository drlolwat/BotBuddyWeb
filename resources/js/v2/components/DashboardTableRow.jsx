import {router} from "@inertiajs/react";
import React from "react";

const DashboardTableRow = ({ account, openDropdownId, handleToggleDropdown, stop, start }) => {
    const icon = (() => {
        if (["Starting", "Stopping", "Queued"].includes(account.status)) {
            return <div className="h-2.5 w-2.5 rounded-full bg-yellow-500 me-2"></div>;
        }
        if (account.status === "Running") {
            return <div className="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>;
        }
        return <div className="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>;
    })();

    let status = account.status_formatted;

    return (
        <tr className="border-b dark:border-gray-700">
            <th scope="row"
                className="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <a title={account.email} href={`/account/${account.id}`}>
                    {account.stats?.name || account.email}
                </a>
            </th>
            <td className="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                {account.stats?.gp ? (
                    <>
                        <img className="inline mb-[2px]" src="/gp.png"
                             alt="gp"/> {account.stats.gp_formatted}
                    </>
                ) : (
                    "-"
                )}
            </td>
            <td className="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                {account.stats?.qp ? (
                    <>
                        <img className="inline mb-[2px]" src="/qp.png"
                             alt="qp"/> {account.stats.qp}
                    </>
                ) : (
                    "-"
                )}
            </td>
            <td className="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                {account.stats?.ttl ? (
                    <>
                        <img className="inline mb-[2px]" src="/ttl.webp"
                             alt="ttl"/> {account.stats.ttl}
                    </>
                ) : (
                    "-"
                )}
            </td>
            <td className="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                {account.account_group ? (
                    <a className="font-medium"
                       href={`/account-group/${account.account_group_id}`}>
                        {account.account_group.name}
                    </a>
                ) : (
                    <span>-</span>
                )}
            </td>
            <td className="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                {account.account_group.agent ? (
                    <a className="font-medium"
                       href={`/agent/${account.account_group.agent_id}`}>
                        {account.account_group.agent.name}
                    </a>
                ) : (
                    <span>-</span>
                )}
            </td>
            <td className="px-4 py-3 text-gray-900 whitespace-nowrap dark:text-white">
                {account.account_group?.script ? (
                    <a className="font-medium"
                       href={`/script/${account.account_group.script_id}`}>
                        {account.account_group.script.name}
                    </a>
                ) : (
                    <span>-</span>
                )}
            </td>
            <td className="px-4 py-3">
                <div className="flex items-center">
                    {icon} {status}
                </div>
            </td>
            <td className="px-4 py-3 flex items-center justify-end">
                <button
                    id={`account-${account.id}-dropdown-button`}
                    data-dropdown-toggle={`account-${account.id}-dropdown`}
                    className="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                    type="button"
                    onClick={(e) => {
                        e.stopPropagation();
                        handleToggleDropdown(`account-${account.id}-dropdown`);
                    }}
                >
                    <svg
                        className="w-5 h-5"
                        aria-hidden="true"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </button>
                <div
                    id={`account-${account.id}-dropdown`}
                    className={`mt-[7.75rem] mr-[-1rem] ${
                        openDropdownId === `account-${account.id}-dropdown` ? "absolute" : "hidden"
                    } z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600`}
                >
                    <ul className="py-1 text-sm text-gray-700 dark:text-gray-200">
                        {["Running", "Starting", "Completed"].includes(account.status) && (
                            <li>
                                <button
                                    onClick={() => {
                                        stop();
                                        router.post(`/account/stop/${account.id}`);
                                        start();
                                    }}
                                    className="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    Stop
                                </button>
                            </li>
                        )}
                        {["Stopped", "Stopping", "Banned", "NoScript"].includes(account.status) && (
                            <li>
                                <button
                                    onClick={() => {
                                        stop();
                                        router.post(`/account/start/${account.id}`);
                                        start();
                                    }}
                                    className="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    Start
                                </button>
                            </li>
                        )}
                        {account.status === "Queued" && (
                            <li>
                                <button
                                    onClick={() => {
                                        stop();
                                        router.post(`/account/dequeue/${account.id}`);
                                        start();
                                    }}
                                    className="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    Cancel
                                </button>
                            </li>
                        )}
                    </ul>
                    <div className="py-1">
                        <a
                            href={`/account/${account.id}`}
                            className="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                        >
                            Edit
                        </a>
                    </div>
                </div>
            </td>
        </tr>
    );
}

export default DashboardTableRow;
