import React, { useEffect, useState } from 'react';


const initalProviderState = {
    paymentDetails: null,
    error: null,
    token: null,
    loading: true,
    setProviderState: () => { },
}


export const ProviderContext = React.createContext(initalProviderState)


export default function PaymentProvider({ children }) {
    let [providerState, setProviderState] = useState({
        paymentDetails: null,
        error: null,
        token: null,
        loading: true,
    })
    const value = { providerState, setProviderState }
    useEffect(() => {
        setProviderState({ ...providerState, loading: true });
        const address = window.location.search;
        let params = new URLSearchParams(address);
        let token = params.get('token');


        (async function () {
            let res = await fetch("http://covpay.com/api/payment/details?token=" + token);
            if (!res.ok) {
                let resData = await res.json();
                setProviderState({ ...providerState, loading: false, error: resData['message'] })
                alert("Failed to start ," + resData['message']);
                return;
            }
            let resData = await res.json();
            setProviderState({ ...providerState, paymentDetails: resData, loading: false, token });
        })();

    }, []);

    return <ProviderContext.Provider value={value}>
        {children}
    </ProviderContext.Provider>
}