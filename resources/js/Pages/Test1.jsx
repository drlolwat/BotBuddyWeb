import {Head, Link} from '@inertiajs/react'

const Test1 = ({ user }) => {
    return (
        <div>
            <Head title="Test1" />
            <div>Test1 - Hello {user}</div>
            <div className="font-bold"><Link href="/test1">Test1</Link></div>
            <div><Link href="/test2">Test2</Link></div>
            <div><Link href="/test3">Test3</Link></div>
        </div>
    )
}

export default Test1;
