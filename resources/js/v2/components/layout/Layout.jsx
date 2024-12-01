import Sidenav from "./Sidenav.jsx";
import {Fragment} from "react";
import Header from "./Header.jsx";
import Main from "./Main.jsx";

const Layout = ({global, errors, flash, children}) => {
    return (
        <Fragment>
            <Header global={global} />
            <Sidenav />
            <Main errors={errors} flash={flash}>
                {children}
            </Main>
        </Fragment>
    )
}

export default Layout;
