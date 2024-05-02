import {memo} from "react";

export const Element = memo(({className, children}) => {
    return (
        <div className={className + " flex flex-col gap-1 border-b border-blue-200 my-2 py-2"}>
            {children}
        </div>
    )
})
