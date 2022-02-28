document.addEventListener("DOMContentLoaded", () => {
    const price = document.querySelector(".price").textContent;
    const installment = document.querySelector(".parcelamento");

    const onlyNumbers = price.substring(2);

    const regReplace = onlyNumbers.replace(/\D/, "");

    const strToNum = Number(regReplace);

    const installmentRule = strToNum / 10;

    const numToStr = String(installmentRule);

    if (numToStr.length >= 5) {
        const points = numToStr.substring(0, 2);
        const rest = numToStr.substring(2, 5);
        const formatedPrice = `${points}.${rest}`;

        const installmentRender = `Parcele em até 10x com parcela mínima de R$ ${formatedPrice},00`;

        installment.textContent = installmentRender;
    } else if (numToStr.length === 3) {
        const points = numToStr.substring(0, 3);
        const formatedPrice = `${points}`;
        const installmentRender = `Parcele em até 10x com parcela mínima de R$ ${points},00`;

        installment.textContent = installmentRender;
    } else {
        const installmentRender = `Parcele em até 10x com parcela mínima de R$ ${numToStr.substring(
            0,
            2
        )},00`;

        installment.textContent = installmentRender;
    }
});
