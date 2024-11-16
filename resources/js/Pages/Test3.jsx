import {Head, Link} from '@inertiajs/react'

const Test3 = () => {
    return (
        <div>
            <Head title="Test3" />
            <div>Test3</div>
            <div className="grid gap-2">
                <div className="bg-pink-500 text-white p-2"><Link href="/test1">Test1</Link></div>
                <div><Link href="/test2">Test2</Link></div>
                <div className="font-bold"><Link href="/test3">Test3</Link></div>
            </div>
        </div>
    )
}

export default Test3;
