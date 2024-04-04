import {useState} from "react";

const MultiSelect = () => {

    const [selectedDays, setSelectedDays] = useState([]);

    const days = {
        Monday: 1,
        Tuesday: 2,
        Wednesday: 3,
        Thursday: 4,
        Friday: 5,
        Saturday: 6,
        Sunday: 7,
    };

    const toggleDay = (day) => {
        if (day === "0") {
            return;
        }
        if (selectedDays.includes(day)) {
            setSelectedDays(selectedDays.filter(d => d !== day));
        } else {
            setSelectedDays([...selectedDays, day]);
        }
    }

    return (
        <div>
            {selectedDays.map(day => (
                <input type="hidden" name="days[]" value={days[day]} />
            ))}
            <label className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Days</label>
            {selectedDays.length > 0 && <div
                className="w-full flex flex-wrap gap-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg rounded-b-none focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                {selectedDays.map(day => (
                    <div key={day} className="gap-2 bg-white text-black px-2 py-1 rounded-md inline-flex">
                        <div>{day}</div>
                        <div className="cursor-pointer" onClick={() => toggleDay(day)}>
                            <svg className="w-3.5 mt-[.3rem] h-3.5" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                      d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                            </svg>
                        </div>
                    </div>
                ))}
            </div>}
            <select
                className={`bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ${selectedDays.length > 0 ? 'rounded-t-none' : ''} focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500`}
                onChange={e => toggleDay(e.target.value)} value="0">
                <option value="0">Select a day</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
                <option>Sunday</option>
            </select>
        </div>
    )
}

export default MultiSelect;
