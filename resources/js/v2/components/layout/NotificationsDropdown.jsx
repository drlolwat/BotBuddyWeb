import React, { useState, useRef, useEffect } from 'react';
import {Link} from "@inertiajs/react";

const NotificationDropdown = ({ notificationCount, notifications, viewAllNotificationsUrl = '/notifications'}) => {
    const [isOpen, setIsOpen] = useState(false);
    const dropdownRef = useRef(null);

    const toggleDropdown = () => {
        setIsOpen((prev) => !prev);
    };

    const closeDropdown = () => {
        setIsOpen(false);
    };

    const handleOutsideClick = (event) => {
        if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
            closeDropdown();
        }
    };

    useEffect(() => {
        document.addEventListener('click', handleOutsideClick);
        return () => {
            document.removeEventListener('click', handleOutsideClick);
        };
    }, []);

    return (
        <div className="relative inline-block text-left" ref={dropdownRef}>
            <button
                type="button"
                onClick={(e) => {
                    e.stopPropagation();
                    toggleDropdown();
                }}
                className="p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            >
                <span className="sr-only">View notifications</span>
                {notificationCount > 0 && (
                    <span className="absolute bg-blue-500 text-blue-100 px-2 py-1 text-[.6rem] font-bold rounded-full -top-1 -right-1">
            {notificationCount > 9 ? "9+" : notificationCount}
          </span>
                )}
                <svg
                    aria-hidden="true"
                    className="w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                </svg>
            </button>
            <div
                className={`${
                    isOpen ? 'absolute' : 'hidden'
                } overflow-hidden z-50 my-4 text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg dark:divide-gray-600 dark:bg-gray-700 rounded-xl right-0 top-full mt-1 w-[300px]`}
            >
                <div className="block py-2 px-4 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-600 dark:text-gray-300">
                    Notifications
                </div>
                <div>
                    {notificationCount === 0 && (
                        <div className="text-gray-500 font-normal text-sm dark:text-gray-400 flex py-3 px-4">
                            You have no new notifications.
                        </div>
                    )}
                    {notificationCount > 0 &&
                        notifications.slice(0, 3).map((notification) => (
                            <a
                                href="#"
                                key={notification.id}
                                className="flex py-3 px-4 border-b dark:border-gray-600"
                            >
                                <div className="pl-3 w-full">
                                    <div className="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">
                                        {notification.message}
                                    </div>
                                    <div className="text-xs font-medium text-primary-600 dark:text-primary-500">
                                        {notification.created_at}
                                    </div>
                                </div>
                            </a>
                        ))}
                </div>
                <Link
                    href={viewAllNotificationsUrl}
                    className="block py-2 text-md font-medium text-center text-gray-900 bg-gray-100 hover:bg-gray-100 dark:bg-gray-600 dark:text-white dark:hover:underline"
                >
                    <div className="inline-flex items-center">
                        <svg
                            aria-hidden="true"
                            className="mr-2 w-4 h-4 text-gray-500 dark:text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            <path
                                fillRule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clipRule="evenodd"
                            ></path>
                        </svg>
                        View all
                    </div>
                </Link>
            </div>
        </div>
    );
};

export default NotificationDropdown;
