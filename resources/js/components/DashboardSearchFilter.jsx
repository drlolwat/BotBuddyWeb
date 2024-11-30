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
import {router} from '@inertiajs/react';

const statuses = [
    // {
    //     value: "Starting",
    //     label: "Starting",
    // },
    {
        value: "Running",
        label: "Running",
    },
    {
        value: "Completed",
        label: "Completed",
    },
    // {
    //     value: "Stopping",
    //     label: "Stopping",
    // },
    // {
    //     value: "Stopped",
    //     label: "Stopped",
    // },
    // {
    //     value: "Banned",
    //     label: "Banned",
    // },
    {
        value: "NoScript",
        label: "NoScript",
    },
    {
        value: "ProxyBlocked",
        label: "ProxyBlocked",
    },
]

const DashboardSearchFilter = ({status, account_group_id}) => {

    const [accountGroups, setAccountGroups] = React.useState([]);
    const [filters, setFilters] = React.useState({
        accountGroup: "",
        status: new URL(window.location.href).searchParams.get('status') || "",
    });

    const params = new URL(window.location.href).searchParams;
    const accountGroup = accountGroups.find(group => parseInt(group.id, 10) === parseInt(params.get('account_group_id'), 10));
    console.log(filters);
    const [value, setValue] = React.useState(accountGroup?.value || "");

    useEffect(() => {
        async function loadAccountGroups() {
            const groups = await fetchAccountGroups();
            const mappedGroups = groups.map(group => ({
                value: group.label,
                label: group.label,
                id: group.value,
            }));

            setAccountGroups(mappedGroups);

            const params = new URL(window.location.href).searchParams;
            const accountGroupId = parseInt(params.get('account_group_id'), 10);
            const matchedGroup = mappedGroups.find(group => parseInt(group.id, 10) === accountGroupId);

            setFilters(prevFilters => ({
                ...prevFilters,
                accountGroup: matchedGroup?.value || "",
            }));
        }

        loadAccountGroups();
    }, []);

    const [open, setOpen] = React.useState(false)
    const [statusOpen, setStatusOpen] = React.useState(false)

    const applyFilters = () => {
        let queryFilters = {};
        if (filters.accountGroup !== '') {
            const selectedGroup = accountGroups.find((group) => group.value === filters.accountGroup);
            if (selectedGroup) {
                queryFilters.account_group_id = selectedGroup.id;
            }
        }
        if (filters.status !== '') {
            queryFilters.status = filters.status;
        }

        const currentUrlParams = new URLSearchParams(window.location.search);
        currentUrlParams.delete('page');

        Object.entries(queryFilters).forEach(([key, val]) => {
            if (val !== '') {
                currentUrlParams.set(key, val);
            }
        });

        ['account_group_id', 'status'].forEach((key) => {
            if (!(key in queryFilters)) {
                currentUrlParams.delete(key);
            }
        });

        router.visit(`/dashboard?${currentUrlParams.toString()}`, {
            method: 'get',
            preserveScroll: true,
            preserveState: false, // set to true will keep the filters box open
        });
    };

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
                <div className="font-bold text-gray-900 dark:text-white">Online accounts</div>
            </div>
            <div>
                <Popover>
                    {(status || account_group_id) && <PopoverTrigger>
                        <div
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
                        </div>
                    </PopoverTrigger>}
                    {
                        <PopoverContent>
                            <div className="grid gap-2">
                                {
                                    <>
                                        <div>Account group</div>
                                        <ComboBox
                                            open={open}
                                            setOpen={setOpen}
                                            value={filters.accountGroup}
                                            setValue={(newValue) =>
                                                setFilters((prevFilters) => ({
                                                    ...prevFilters,
                                                    accountGroup: newValue,
                                                }))
                                            }
                                            options={accountGroups}
                                            select="Select an account group"
                                            search="Search account groups..."
                                            empty="No account groups found."
                                        />
                                    </>
                                }
                                {
                                    <>
                                        <div>Status</div>
                                        <ComboBox
                                            open={statusOpen}
                                            setOpen={setStatusOpen}
                                            value={filters.status}
                                            setValue={(newValue) =>
                                                setFilters((prevFilters) => ({
                                                    ...prevFilters,
                                                    status: newValue,
                                                }))
                                            }
                                            options={statuses}
                                            select="Select a status"
                                        />
                                    </>
                                }
                                <Button onClick={applyFilters}>Apply</Button>
                            </div>
                        </PopoverContent>
                    }
                </Popover>
            </div>
        </>
    )
}

export default DashboardSearchFilter;
