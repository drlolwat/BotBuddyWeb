import * as React from "react";
import {Button} from "./ui/button";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "./ui/popover";
import ComboBox from "./ComboBox";
import {fetchAccountGroups} from "../utils/fetchUtils.js";
import {useEffect} from "react";

const statuses = [
    {
        value: "Starting",
        label: "Starting",
    },
    {
        value: "Running",
        label: "Running",
    },
    {
        value: "Completed",
        label: "Completed",
    },
    {
        value: "Stopping",
        label: "Stopping",
    },
    {
        value: "Stopped",
        label: "Stopped",
    },
    {
        value: "Banned",
        label: "Banned",
    },
    {
        value: "NoScript",
        label: "NoScript",
    },
    {
        value: "ProxyBlocked",
        label: "ProxyBlocked",
    },
]

const AccountSearchFilter = ({status, account_group_id}) => {

    const [accountGroups, setAccountGroups] = React.useState([]);

    useEffect(async () => {
        const groups = await fetchAccountGroups();
        setAccountGroups(groups.map(group => ({ value: group.label, label: group.label, id: group.value })));
    }, []);

    const params = new URL(window.location.href).searchParams;

    const [open, setOpen] = React.useState(false)
    const accountGroup = accountGroups.find(group => group.id === parseInt(params.get('account_group_id'), 10));
    const [value, setValue] = React.useState(accountGroup?.value || "");

    const [statusOpen, setStatusOpen] = React.useState(false);
    const [statusValue, setStatusValue] = React.useState(params.get('status') || "");

    const applyFilters = () => {
        if (value === '' && statusValue === '') {
            return;
        }

        let filters = {};
        if (value !== '') {
            filters.account_group_id = accountGroups.find((group) => group.value === value).id;
        }
        if (statusValue !== '') {
            filters.status = statusValue;
        }

        const queryString = new URLSearchParams(filters);


        let redirect = "/account?";
        window.location.href = `${redirect}${queryString.toString()}`;
    }

    const filtersCountFn = () => {
        const params = new URL(window.location.href).searchParams;
        let count = 0;
        for (const [key, value] of params.entries()) {
            if (key === 'status' || key === 'account_group_id') {
                count++;
            }
        }
        return count;
    }

    const [filtersCount, setFiltersCount] = React.useState(filtersCountFn());

    return (
        <>
            <div className="w-full md:w-1/2">
                <div className="font-bold text-gray-900 dark:text-white">Accounts</div>
            </div>
            {/*<div*/}
            {/*    className="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">*/}
            {/*    <a href="#"*/}
            {/*       className="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">*/}
            {/*        <svg className="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"*/}
            {/*             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">*/}
            {/*            <path clipRule="evenodd" fillRule="evenodd"*/}
            {/*                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>*/}
            {/*        </svg>*/}
            {/*        Add accounts*/}
            {/*    </a>*/}
            {/*</div>*/}
            <div
                className="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                <a href="/account/create"
                   className="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <svg className="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clipRule="evenodd" fillRule="evenodd"
                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                    </svg>
                    Add accounts
                </a>
                <Popover>
                    {(status || account_group_id) && <PopoverTrigger>
                        <button
                            className="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                            type="button">
                            <span className="sr-only">Filters button</span>
                            Filters
                            {filtersCount > 0 && <span
                                className="inline-flex justify-center items-center ms-2.5 w-[1.5rem] h-5 text-xs font-semibold rounded-full text-primary-800 bg-primary-100 dark:bg-primary-200 dark:text-primary-800">{filtersCount}</span>}
                            <svg className="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round"
                                      strokeWidth="2" d="m1 1 4 4 4-4"></path>
                            </svg>
                        </button>
                    </PopoverTrigger>}
                    {(status || account_group_id) && <PopoverContent>
                        <div className="grid gap-2">
                            {account_group_id && <>
                                <div>Account group</div>
                                <ComboBox
                                    open={open} setOpen={setOpen} value={value} setValue={setValue}
                                    options={accountGroups}
                                    select="Select an account group"
                                    search="Search account groups..."
                                    empty="No account groups found."
                                /></>}
                            {status && <>
                                <div>Status</div>
                                <ComboBox
                                    open={statusOpen} setOpen={setStatusOpen} value={statusValue}
                                    setValue={setStatusValue}
                                    options={statuses}
                                    select="Select a status"
                                /></>}
                            {(!(value === '' && statusValue === '')) && <Button onClick={applyFilters}>Apply
                            </Button>
                            }
                        </div>
                    </PopoverContent>}
                </Popover>
            </div>
        </>
    )
}

export default AccountSearchFilter;
