import React from "react";

const DashboardCard = ({ label, value }) => (
    <div
        className="overflow-hidden rounded-lg bg-white border border-gray-200 px-4 py-5 dark:bg-gray-800 dark:border-gray-700">
        <dt className="mb-2 text-sm font-medium tracking-tight text-gray-900 dark:text-white">{label}</dt>
        <dd className="text-3xl font-semibold tracking-tight text-gray-700 dark:text-gray-400">{value}</dd>
    </div>
);

export default DashboardCard;
