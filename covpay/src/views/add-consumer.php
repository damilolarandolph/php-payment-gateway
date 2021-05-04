<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/common/css/tailwind.min.css">
    <title>Add Consumer</title>
</head>

<body>
    <section class="bg-blue-600 h-screen">
        <div class="mx-auto flex justify-center lg:items-center h-full">
            <form method="POST" id="login" class="w-full sm:w-4/6 md:w-3/6 lg:w-4/12 xl:w-3/12 text-white py-12 px-2 sm:px-0">
                <div class="pt-0 px-2 flex flex-col items-center justify-center">
                    <svg width="129" height="129" viewBox="0 0 129 129" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4.3" y="4.3" width="120.4" height="120.4" stroke="url(#paint0_linear)" stroke-width="8.6" />
                        <path d="M48.4073 41.3445C48.3081 43.4987 47.727 45.4049 46.6641 47.0631C45.6012 48.7071 44.106 49.9826 42.1785 50.8896C40.2653 51.7967 38.0756 52.2502 35.6096 52.2502C31.5422 52.2502 28.3392 50.9251 26.0008 48.2748C23.6623 45.6246 22.4931 41.8831 22.4931 37.0503V35.5197C22.4931 32.4868 23.0175 29.8366 24.0662 27.569C25.1292 25.2872 26.6527 23.5299 28.6368 22.2969C30.621 21.0497 32.9169 20.4261 35.5246 20.4261C39.2803 20.4261 42.299 21.4182 44.5807 23.4023C46.8625 25.3723 48.1593 28.0934 48.4711 31.5656H41.0306C40.9739 29.6807 40.4991 28.3272 39.6062 27.5052C38.7134 26.6832 37.3528 26.2722 35.5246 26.2722C33.668 26.2722 32.3075 26.9667 31.443 28.3556C30.5784 29.7445 30.1249 31.9624 30.0824 35.0095V37.1991C30.0824 40.5013 30.4934 42.861 31.3154 44.2782C32.1516 45.6955 33.583 46.4041 35.6096 46.4041C37.3245 46.4041 38.6354 46.0002 39.5425 45.1923C40.4495 44.3845 40.9314 43.1019 40.9881 41.3445H48.4073ZM78.148 36.9865C78.148 40.0052 77.5882 42.6767 76.4686 45.001C75.3489 47.3253 73.7546 49.1181 71.6854 50.3794C69.6304 51.6266 67.2849 52.2502 64.6488 52.2502C62.0128 52.2502 59.6743 51.6478 57.6335 50.4432C55.5927 49.2244 53.9983 47.4883 52.8503 45.2349C51.7165 42.9814 51.1213 40.395 51.0646 37.4755V35.7323C51.0646 32.6994 51.6173 30.0279 52.7228 27.7178C53.8424 25.3935 55.4368 23.6007 57.5059 22.3394C59.5893 21.0781 61.9561 20.4474 64.6063 20.4474C67.2282 20.4474 69.5666 21.071 71.6216 22.3181C73.6766 23.5653 75.271 25.3439 76.4048 27.654C77.5527 29.95 78.1338 32.586 78.148 35.5622V36.9865ZM70.5587 35.6898C70.5587 32.6144 70.0414 30.283 69.0068 28.6957C67.9864 27.0942 66.5196 26.2935 64.6063 26.2935C60.8648 26.2935 58.8877 29.0996 58.6752 34.7119L58.6539 36.9865C58.6539 40.0194 59.157 42.3508 60.1633 43.9806C61.1695 45.6104 62.6647 46.4253 64.6488 46.4253C66.5337 46.4253 67.9864 45.6246 69.0068 44.0231C70.0272 42.4216 70.5445 40.1186 70.5587 37.1141V35.6898ZM93.5392 43.4066L99.7042 20.8726H108.038L97.6208 51.825H89.4575L79.1259 20.8726H87.3955L93.5392 43.4066ZM35.0994 92.3233V102.825H27.6377V71.8726H39.9889C42.3557 71.8726 44.4461 72.3119 46.2602 73.1906C48.0884 74.0551 49.4986 75.2952 50.4906 76.9108C51.4969 78.5123 52 80.3335 52 82.3743C52 85.393 50.9158 87.8094 48.7474 89.6234C46.5932 91.4233 43.6312 92.3233 39.8613 92.3233H35.0994ZM35.0994 86.5622H39.9889C41.4345 86.5622 42.5328 86.2008 43.284 85.478C44.0493 84.7552 44.4319 83.7348 44.4319 82.4168C44.4319 80.9712 44.0422 79.8162 43.2627 78.9517C42.4832 78.0871 41.4203 77.6478 40.0739 77.6336H35.0994V86.5622ZM69.7934 97.0427H59.568L57.7823 102.825H49.8103L61.1624 71.8726H68.1777L79.6148 102.825H71.6004L69.7934 97.0427ZM61.3537 91.2816H68.0077L64.6701 80.546L61.3537 91.2816ZM90.0315 85.308L95.7288 71.8726H103.786L93.8155 91.7068V102.825H86.2475V91.7068L76.2985 71.8726H84.313L90.0315 85.308Z" fill="url(#paint1_linear)" />
                        <defs>
                            <linearGradient id="paint0_linear" x1="119.899" y1="-14.6411" x2="-33.2393" y2="138.497" gradientUnits="userSpaceOnUse">
                                <stop offset="0.0255896" stop-color="#86FF92" />
                                <stop offset="1" stop-color="#4EBDFC" />
                            </linearGradient>
                            <linearGradient id="paint1_linear" x1="-29.7058" y1="-2.37872" x2="146.243" y2="154.088" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#86FF92" />
                                <stop offset="1" stop-color="#4EBDFC" />
                            </linearGradient>
                        </defs>
                    </svg>


                </div>
                <div class="pt-16 px-2 flex flex-col items-center justify-center">
                    <h3 class="text-2xl sm:text-3xl xl:text-2xl font-bold leading-tight">Get Your API Keys</h3>
                </div>
                <div class="mt-12 w-full px-2 sm:px-6">
                    <div class="flex flex-col mt-5">
                        <label for="name" class="text-lg font-semibold leading-tight">Merchant Name</label>
                        <input required id="name" name="text" class="h-10 px-2 w-full text-white bg-blue-700 rounded mt-2 focus:outline-none shadow" type="text" />
                    </div>
                    <div class="flex flex-col mt-5">
                        <label for="bank-name" class="text-lg font-semibold fleading-tight">Your Bank</label>
                        <select required id="bank-name" name="bankBIC" class="h-10 px-2 w-full text-white bg-blue-700 rounded mt-2 focus:outline-none shadow" type="text">
                            <option value="ZENITH_BANK">Zenith Bank</option>
                        </select>
                    </div>
                    <div class="flex flex-col mt-5">
                        <label for="accountNumber" class="text-lg font-semibold fleading-tight">Account Number</label>
                        <input required id="accountNumber" name="accountNumber" class="h-10 px-2 w-full text-white bg-blue-700 rounded mt-2 focus:outline-none shadow" type="number" controls=false />
                    </div>
                </div>

                <div class="px-2 sm:px-6">
                    <button class="focus:outline-none w-full bg-white transition duration-150 ease-in-out hover:bg-gray-200 rounded text-indigo-600 px-8 py-3 text-sm mt-6">Get API Keys</button>
                </div>
            </form>
        </div>

    </section>
</body>

</html>