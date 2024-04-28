import {Link} from "@inertiajs/react";

export const DefaultLink = ({href, children}) => {

    return (
        <Link href={href} className="text-blue-500 hover:underline">{children}</Link>
    )
}
