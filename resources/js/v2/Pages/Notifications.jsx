import React from "react";
import { Head, Link } from "@inertiajs/react";
import Layout from "../components/Layout.jsx";

const Notifications = ({ notifications, flash, errors, global }) => {
    const currentPage = notifications.current_page;
    const lastPage = notifications.last_page;
    const perPage = notifications.per_page;
    const total = notifications.total;

    // Generate the range of pages to display
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

    return (
        <Layout global={global} flash={flash} errors={errors}>
            <Head title="Notifications" />
            <div className="mx-auto">
                <div className="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                    {/* Header */}
                    <div className="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div className="w-full md:w-1/2">
                            <div className="font-bold text-gray-900 dark:text-white">Notifications</div>
                        </div>
                        <div className="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <Link
                                href={'/notifications/clear'}
                                className="flex items-center justify-center text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-primary-800"
                            >
                                Clear all
                            </Link>
                        </div>
                    </div>

                    {/* Notifications Table */}
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" className="px-4 py-3">Type</th>
                                <th scope="col" className="px-4 py-3">Message</th>
                                <th scope="col" className="px-4 py-3">Received</th>
                            </tr>
                            </thead>
                            <tbody>
                            {notifications.data.map((notification, index) => (
                                <tr
                                    key={index}
                                    className="border-b dark:border-gray-700"
                                >
                                    <td
                                        className={`px-4 py-3 ${
                                            !notification.opened
                                                ? "font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                : ""
                                        }`}
                                    >
                                        {notification.type}
                                    </td>
                                    <td
                                        className={`px-4 py-3 ${
                                            !notification.opened
                                                ? "font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                : ""
                                        }`}
                                    >
                                        {notification.message}
                                    </td>
                                    <td
                                        className={`px-4 py-3 ${
                                            !notification.opened
                                                ? "font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                                : ""
                                        }`}
                                    >
                                        {notification.created_at}
                                    </td>
                                </tr>
                            ))}
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
                                                href={`?page=1`}
                                                className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                            >
                                                1
                                            </Link>
                                        </li>
                                        {pages[0] > 2 && (
                                            <li>
                                        <span className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
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
                                                href={`?page=${page}`}
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
                                        <span className="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                                            ...
                                        </span>
                                            </li>
                                        )}
                                        <li>
                                            <Link
                                                href={`?page=${lastPage}`}
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
        </Layout>
    );
};

export default Notifications;
