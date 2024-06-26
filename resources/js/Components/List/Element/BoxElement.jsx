import {memo} from "react";

export const BoxElement = memo(({className, children}) => {
    return (
        <div className={"grow basis-48 border border-blue-200 m-2 p-2 rounded-lg " + className}>
            {children}
        </div>
    )
})
