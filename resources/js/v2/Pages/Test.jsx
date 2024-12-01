import Layout from "../components/layout/Layout.jsx";

const Test = ({ global, flash, errors }) => {
    return (
        <Layout global={global} flash={flash} errors={errors}>
            <div>test</div>
        </Layout>
    )
}

export default Test;
