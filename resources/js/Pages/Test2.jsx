import {Head, Link} from '@inertiajs/react'

const Test2 = ({ now }) => {
    return (
        <div>
            <Head title="Test2" />
            <div>Test2 - {now}</div>
            <div><Link href="/test1">Test1</Link></div>
            <div className="font-bold"><Link href="/test2">Test2</Link></div>
            <div><Link href="/test3">Test3</Link></div>
        </div>
    )
}

export default Test2;
