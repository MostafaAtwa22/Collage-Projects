--n_bit six_operation_ALU

library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;
entity alu is
    generic(
        INPUT_WIDTH : integer := 32
    );
    port(
        in1 : in std_logic_vector(INPUT_WIDTH-1 downto 0) := (others => '0');
        in2 : in std_logic_vector(INPUT_WIDTH-1 downto 0) :=  (others => '0');
        control : in std_logic_vector(5 downto 0) := (others => '0');
        alu_result : out std_logic_vector(INPUT_WIDTH-1 downto 0);
        zero : out std_logic
    );
end entity;

architecture rtl of alu is
    signal result : std_logic_vector(INPUT_WIDTH-1 downto 0);
    begin
        process(in1,in2,control) is
            begin
                case control is
                    when (others => '0') =>  -- logical and
                        result <= in1 and in2;
                    when (0 to control'length-2 => '0') & '1' =>  -- logical or
                        result <= in1 or in2;
                    when (0 to control'length-3 => '0') & "10"=>  -- add
                        result <= std_logic_vector(signed(in1)+signed(in2));
                    when (0 to control'length-3 => '0') & "11" =>  -- sub
                        result <= std_logic_vector(signed(in1)-signed(in2));
                    when (0 to control'length-4 => '0') & "100"=>  -- set lower than
                        if(in1 < in2) then
                            result <= std_logic_vector(to_unsigned(1,INPUT_WIDTH));
                        else
                            result <= (others => '0');
                        end if;
                    when (0 to control'length-4 => '0') & "101" =>  -- logic nor
                        result <= in1 nor in2;
                    when (0 to control'length-4 => '0') & "110" =>  -- bne
                        if(in1 /= in2) then 
                            result <= std_logic_vector(to_unsigned(1,INPUT_WIDTH));
                        else
                            result <= (others => '0');
                        end if;
                    when (0 to control'length-4 => '0') & "111" =>
                        if(in1 = in2) then
                            result <= std_logic_vector(to_unsigned(1,INPUT_WIDTH));
                        else
                            result <= (others => '0');
                        end if;
                    when others => null;    
                        result <= (others => '0');
                end case;
        end process;
        alu_result <= result;
        zero <= result(0);
end architecture;