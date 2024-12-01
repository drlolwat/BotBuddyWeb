import React, {useEffect, useState} from "react";
import {Head, Link, router, usePoll} from "@inertiajs/react";
import Layout from "../components/layout/Layout.jsx";
import DashboardSearchFilter from "../../components/DashboardSearchFilter.jsx";
import TablePagination from "../components/TablePagination.jsx";
import DashboardCard from "../components/DashboardCard.jsx";
import DashboardTableRow from "../components/DashboardTableRow.jsx";

// todo: create table component (with pagination, option to have checkboxes etc.)

const Dashboard = ({flash, errors, global, online, offline, bannedLast24h, accounts}) => {

    usePoll(10000);

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
                <DashboardCard label="Accounts online" value={online}/>
                <DashboardCard label="Accounts offline" value={offline}/>
                <DashboardCard label="Accounts banned past 24h" value={bannedLast24h}/>
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
                                {accounts.data.map((account) => <DashboardTableRow
                                    account={account}
                                    key={account.id}
                                    openDropdownId={openDropdownId}
                                    handleToggleDropdown={handleToggleDropdown}
                                />)}
                                </tbody>
                            </table>
                        </div>
                        <TablePagination {...accounts} />
                    </div>
                </div>
            </section>
        </Layout>
    );
};

export default Dashboard;
