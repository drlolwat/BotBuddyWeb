import React, { useState, Fragment } from 'react';
import DynamicSelect from '../components/DynamicSelect.jsx';

const className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 min-w-[200px] mb-2 mr-2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500";
const skills = ["Magic", "Attack", "Hunter", "Mining", "Prayer", "Ranged", "Slayer", "Agility", "Cooking", "Defence", "Farming", "Fishing", "Crafting", "Herblore", "Smithing", "Strength", "Thieving", "Fletching", "Hitpoints", "Firemaking", "Woodcutting", "Construction", "Runecrafting"];

let statGoalSelect = {
    name: "",
        className,
        options: [
        {label: "Select a stat"},
        {
            label: "GP",
            value: "gp",
            render: (parent, callback) => <Fragment key="gp">
                <input type="number" name="event_stat[gp]" className={className} placeholder="Desired GP"/>
            </Fragment>,
        },
        {
            label: "QP",
            value: "qp",
            render: (parent, callback) => <Fragment key="qp">
                <input type="number" name="event_stat[qp]" className={className} placeholder="Desired QP"/>
            </Fragment>,
        },
        {
            label: "TTL",
            value: "ttl",
            render: (parent, callback) => <Fragment key="ttl">
                <input type="number" name="event_stat[ttl]" className={className} placeholder="Desired TTL"/>
            </Fragment>,
        },
    ],

};

skills.forEach((skill) => {
   statGoalSelect.options.push({
       label: skill,
       value: skill.toLowerCase(),
       render: (parent, callback) => <Fragment key={skill.toLowerCase()}>
           <input type="number" name={`event_stat[${skill.toLowerCase()}]`} className={className} placeholder={`Desired ${skill.toLowerCase()} level`} />
       </Fragment>,
   });
});

const StatGoalForm = () => {
    const [statGoals, setStatGoals] = useState([]);
    if (statGoals.length === 0) {
        const newStatGoal = (
            <div>
                <DynamicSelect
                    key={`statGoal-${statGoals.length}`}
                    {...statGoalSelect}
                />
            </div>
        );
        setStatGoals([...statGoals, newStatGoal]);
    }

    const addStatGoal = (e) => {
        e.preventDefault();
        const newStatGoal = (
            <div>
                <DynamicSelect
                    key={`statGoal-${statGoals.length}`}
                    {...statGoalSelect}
                />
            </div>
        );
        setStatGoals([...statGoals, newStatGoal]);
    };

    return (
        <Fragment>
            <button onClick={addStatGoal} className="mb-2 inline-flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                <svg className="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clipRule="evenodd" fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"></path></svg>
                Another stat goal
            </button>
            {statGoals.map((statGoal) => statGoal)}
        </Fragment>
    );
};

export default StatGoalForm;
