export const BoxList = ({className, children}) => {
    return (
        <div className={"flex flex-wrap gap-1 " + className}>
            {children}
        </div>
    )
}
