import Sidenav from "./Sidenav.jsx";
import {Fragment, useState} from "react";
import Header from "./Header.jsx";
import Main from "./Main.jsx";

const Layout = ({global, errors, flash, children}) => {
    const [isDrawerOpen, setIsDrawerOpen] = useState(false);

    const toggleDrawer = () => {
        setIsDrawerOpen((prev) => !prev);
    };

    return (
        <Fragment>
            <Header onToggleDrawer={toggleDrawer} global={global} />
            <Sidenav isOpen={isDrawerOpen} />
            <Main errors={errors} flash={flash}>
                {children}
            </Main>
        </Fragment>
    )
}

export default Layout;
