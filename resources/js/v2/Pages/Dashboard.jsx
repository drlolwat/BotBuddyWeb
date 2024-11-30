import React, {useEffect, useState} from "react";
import {Head, Link, router, usePoll} from "@inertiajs/react";
import Layout from "../components/Layout.jsx";
import DashboardSearchFilter from "../../components/DashboardSearchFilter.jsx";

// todo: create table component (with pagination, option to have checkboxes etc.)

const Dashboard = ({flash, errors, global, online, offline, bannedLast24h, accounts}) => {

    usePoll(10000);

    // todo: move pagination into context+component
    const currentPage = accounts.current_page;
    const lastPage = accounts.last_page;
    const perPage = accounts.per_page;
    const total = accounts.total;

    const getPageRange = () => {
        const pages = [];
        for (let i = currentPage - 3; i <= currentPage + 3; i++) {
            if (i === lastPage || i === currentPage || (i > 0 && i < lastPage)) {
                pages.push(i);
            }
        }
        return pages;
    };

    const pages = getPageRange();

    const getPageUrl = (page) => {
        const query = new URLSearchParams(window.location.search);
        query.delete('page');
        query.set('page', page);
        return `?${query.toString()}`;
    }

    // todo: move dropdown functionality into context+component
    const [openDropdownId, setOpenDropdownId] = useState(null);

    const handleToggleDropdown = (dropdownId) => {
        setOpenDropdownId((prevId) => (prevId === dropdownId ? null : dropdownId));
    };

    const closeAllDropdowns = () => {
        setOpenDropdownId(null);
    };

    useEffect(() => {
        const handleOutsideClick = (event) => {
            if (!event.target.closest("[data-dropdown-toggle]")) {
                closeAllDropdowns();
            }
        };

        document.addEventListener("click", handleOutsideClick);
        return () => {
            document.removeEventListener("click", handleOutsideClick);
        };
    }, []);

    return (
        <Layout global={global} flash={flash} errors={errors}>
            <Head title="Dashboard"/>
            <dl className="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div
                    className="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
                    <dt className="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts
                        online
                    </dt>
                    <dd className="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{online}</dd>
                </div>
                <div
                    className="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
                    <dt className="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts
                        offline
                    </dt>
                    <dd className="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{offline}</dd>
                </div>
                <div
                    className="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
                    <dt className="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">Accounts
                        banned past 24h
                    </dt>
                    <dd className="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{bannedLast24h}</dd>
                </div>
            </dl>
            <section className="bg-gray-50 dark:bg-gray-900 mt-4">
                <div className="mx-auto">
                    <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                        <div
                            className="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <DashboardSearchFilter status={true} account_group_id={true}/>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" className="px-4 py-3">Name</th>
                                    <th scope="col" className="px-4 py-3">GP</th>
                                    <th scope="col" className="px-4 py-3">QP</th>
                                    <th scope="col" className="px-4 py-3">TTL</th>
                                    <th scope="col" className="px-4 py-3">Group</th>
                                    <th scope="col" className="px-4 py-3">Agent</th>
                                    <th scope="col" className="px-4 py-3">Script</th>
                                    <th scope="col" className="px-4 py-3">Status</th>
                                    <th scope="col" className="px-4 py-3">
                                        <span className="sr-only">Actions</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {accounts.data.map((account) => {
                                    const icon = (() => {
                                        if (["Starting", "Stopping", "Queued"].includes(account.status)) {
                                            return <div className="h-2.5 w-2.5 rounded-full bg-yellow-500 me-2"></div>;
                                        }
                                        if (account.status === "Running") {
                                            return <div className="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>;
                                        }
                                        return <div className="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>;
                                    })();

                                    let status = account.status;
                                    if (
                                        account.perm_banned_at &&
                                        account.subscription?.name === "Basic"
                                    ) {
                                        status = "Banned";
                                    } else if (account.temp_banned_at) {
                                        status = "Banned (Temporary)";
                                    } else if (account.perm_banned_at) {
                                        status = "Banned (Permanent)";
                                    }

                                    return (
                                        <tr key={account.id} className="border-b dark:border-gray-700">
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
                                                                    onClick={() => router.post(`/account/stop/${account.id}`)}
                                                                    className="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                                    Stop
                                                                </button>
                                                            </li>
                                                        )}
                                                        {["Stopped", "Stopping", "Banned", "NoScript"].includes(account.status) && (
                                                            <li>
                                                                <button
                                                                    onClick={() => router.post(`/account/start/${account.id}`)}
                                                                    className="w-full text-left block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                                                    Start
                                                                </button>
                                                            </li>
                                                        )}
                                                        {account.status === "Queued" && (
                                                            <li>
                                                                <button
                                                                    onClick={() => router.post(`/account/dequeue/${account.id}`)}
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
                                })}
                                </tbody>
                            </table>
                        </div>
                        {/* Pagination */}
                        <div className="flex p-3">
                            <div className="flex-grow">
        <span className="text-sm font-normal text-gray-500 dark:text-gray-400">
            Showing{" "}
            <span className="font-semibold text-gray-900 dark:text-white">
                {Math.max(1, (currentPage - 1) * perPage + 1)}-
                {Math.min(total, currentPage * perPage)}
            </span>{" "}
            of{" "}
            <span className="font-semibold text-gray-900 dark:text-white">
                {total}
            </span>
        </span>
                            </div>
                            <nav>
                                <ul className="inline-flex -space-x-px text-sm">
                                    {!pages.includes(1) && (
                                        <>
                                            <li>
                                                <Link
                                                    href={getPageUrl(1)}
                                                    className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                                >
                                                    1
                                                </Link>
                                            </li>
                                            {pages[0] > 2 && (
                                                <li>
                            <span
                                className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                                ...
                            </span>
                                                </li>
                                            )}
                                        </>
                                    )}
                                    {pages.map((page) => (
                                        <li key={page}>
                                            {page === currentPage ? (
                                                <a
                                                    href="#"
                                                    aria-current="page"
                                                    className="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                                                >
                                                    {page}
                                                </a>
                                            ) : (
                                                <Link
                                                    href={getPageUrl(page)}
                                                    className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                                >
                                                    {page}
                                                </Link>
                                            )}
                                        </li>
                                    ))}
                                    {!pages.includes(lastPage) && (
                                        <>
                                            {pages[pages.length - 1] < lastPage - 1 && (
                                                <li>
                            <span
                                className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                                ...
                            </span>
                                                </li>
                                            )}
                                            <li>
                                                <Link
                                                    href={getPageUrl(lastPage)}
                                                    className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                                >
                                                    {lastPage}
                                                </Link>
                                            </li>
                                        </>
                                    )}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
        </Layout>
    );
};

export default Dashboard;
