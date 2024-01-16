import {useState} from 'react';
import {EventContext} from './context.jsx';

export const EventProvider = ({ children }) => {
    const [event, setEvent] = useState(null);

    const updateEvent = (newEvent) => {
        setEvent(newEvent);
    };

    return (
        <EventContext.Provider value={{ event, updateEvent }}>
            {children}
        </EventContext.Provider>
    );
};
