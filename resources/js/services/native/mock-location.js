import { MockLocation } from "@dewakoding/capacitor-mock-location";

export async function checkMockLocation() {
    try {
        const result = await MockLocation.checkMockLocation();
        return result;
    } catch (e) {
        console.error("Mock Location Check Failed:", e);
        return { isMock: false, error: e };
    }
}
