import Alerts from "./Alerts.jsx";

const Main = ({ children, errors, flash }) => {
    return (
        <main className="p-4 md:ml-64 h-auto pt-20">
            <Alerts errors={errors} flash={flash} />
            {children}
        </main>
    )
};

export default Main;
