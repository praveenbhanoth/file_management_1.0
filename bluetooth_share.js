document.addEventListener("DOMContentLoaded", function () {
    const bluetoothButtons = document.querySelectorAll(".bluetooth-share");

    bluetoothButtons.forEach(button => {
        button.addEventListener("click", async function () {
            const fileUrl = this.getAttribute("data-file");

            if (!navigator.bluetooth) {
                alert("Bluetooth API not supported in this browser!");
                return;
            }

            try {
                // Request a Bluetooth device
                const device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true
                });

                // Simulating file transfer
                alert(`Sending file: ${fileUrl} via Bluetooth to ${device.name}`);

                // Note: Actual file transfer via Bluetooth is not natively supported in browsers yet.
                // This step requires additional backend implementation.

            } catch (error) {
                console.error("Bluetooth transfer failed:", error);
                alert("Bluetooth transfer failed! Try again.");
            }
        });
    });
});
