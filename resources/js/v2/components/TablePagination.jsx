import {Link} from "@inertiajs/react";
import React from "react";

const TablePagination = ({ current_page: currentPage, per_page: perPage, total, last_page: lastPage }) => {

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

    return (
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
    )
}

export default TablePagination;
