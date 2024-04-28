import {Link} from "@inertiajs/react";

export const LendingButton = ({href, children, className = '',...props}) => {
    return (
        <Link
            href={href}
            className={"uppercase bg-gradient-to-tr from-sky-500 via-cyan-300 to-blue-300 px-4 py-2 rounded-lg hover:from-sky-600 hover:via-cyan-400 hover:to-blue-400 active:from-sky-700 active:via-cyan-500 active:to-blue-500 text-gray-800 font-bold " + "" + className}
        >
            {children}
        </Link>
    );
}
