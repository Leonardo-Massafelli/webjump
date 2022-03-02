document.addEventListener("DOMContentLoaded", () => {
    const price = document.querySelector(".price").textContent;
    const installment = document.querySelector(".parcelamento");

    let onlyNumbersBR;
    let onlyNumbersEN;

    if (price.includes("R$")) {
        onlyNumbersBR = price.substring(2);
    } else {
        onlyNumbersEN = price.substring(1);
    }

    if (onlyNumbersBR) {
        const regReplaceBR = onlyNumbersBR.replace(/\D/g, "");

        const strToNum = Number(regReplaceBR);

        const installmentRule = (strToNum / 10).toFixed(2);

        const numToStr = String(installmentRule);

        if (numToStr.length > 8) {
            const points = numToStr.substring(0, 2);
            const rest = numToStr.substring(2, 5);
            const formatedPrice = `${points}.${rest}`;

            const installmentRender = `Parcele em até 10x com parcela mínima de R$ ${formatedPrice}`;

            installment.textContent = installmentRender;
        } else if (numToStr.length === 8) {
            const points = numToStr.substring(0, 3);
            const rest = numToStr.substring(6, 8);
            const formatedPrice = `${points}.${rest}`;
            const installmentRender = `Parcele em até 10x com parcela mínima de R$ ${formatedPrice}`;

            installment.textContent = installmentRender;
        } else {
            const installmentRender = `Parcele em até 10x com parcela mínima de R$ ${numToStr.substring(
                0,
                2
            )}.00`;

            installment.textContent = installmentRender;
        }
    } else {
        const regReplaceEN = onlyNumbersEN.replace(/\D/g, "");

        const strToNum = Number(regReplaceEN);

        const installmentRule = (strToNum / 10).toFixed(2);

        const numToStr = String(installmentRule);
        console.log(numToStr, "NumToStr");

        if (numToStr.length >= 10) {
            const points = numToStr.substring(0, 2);
            const rest = numToStr.substring(1, 4);
            const cents = numToStr.substring(4, 6);
            const formatedPrice = `${points}.${rest}.${cents}`;

            const installmentRender = `Installment up to 10x with a minimum installment of $${formatedPrice}`;

            installment.textContent = installmentRender;
        } else if (numToStr.length === 9) {
            const points = numToStr.substring(0, 1);
            const rest = numToStr.substring(1, 4);
            const cents = numToStr.substring(4, 6);
            const formatedPrice = `${points}.${rest}.${cents}`;
            console.log(formatedPrice);
            const installmentRender = `Installment up to 10x with a minimum installment of $${formatedPrice}`;

            installment.textContent = installmentRender;
        } else if (numToStr.length === 7) {
            const points = numToStr.substring(0, 2);
            const rest = numToStr.substring(2, 4);
            const formatedPrice = `${points}.${rest}`;
            const installmentRender = `Installment up to 10x with a minimum installment of $${formatedPrice}`;

            installment.textContent = installmentRender;
        } else {
            const installmentRender = `Parcele em até 10x com parcela mínima de $${numToStr.substring(
                0,
                2
            )}.00`;

            installment.textContent = installmentRender;
        }
    }
});
